import EditorJS from '@editorjs/editorjs';
import CodeTool from '@editorjs/code';
import Delimiter from '@editorjs/delimiter';
import Header from '@editorjs/header';
import List from '@editorjs/list';
import Quote from '@editorjs/quote';

const editorConfig = window.CATAT_MARKDOWN_CONFIG || {};
const target = document.getElementById('content-md');
const editorJsHolder = document.getElementById('editorjs-holder');
const statusElement = document.getElementById('autosave-status');
const titleInput = document.getElementById('current-title');
const editorForm = document.getElementById('note-editor-form');
const unsavedIndicator = document.getElementById('unsaved-indicator');
const conflictIndicator = document.getElementById('conflict-indicator');
const conflictReloadButton = document.getElementById('conflict-reload-btn');
const conflictComparePanel = document.getElementById('conflict-compare-panel');
const conflictLocalTitle = document.getElementById('conflict-local-title');
const conflictLocalContent = document.getElementById('conflict-local-content');
const conflictServerTitle = document.getElementById('conflict-server-title');
const conflictServerContent = document.getElementById('conflict-server-content');
const conflictApplyLatestButton = document.getElementById('conflict-apply-latest-btn');
const conflictClosePanelButton = document.getElementById('conflict-close-panel-btn');
const originalDocumentTitle = document.title;
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
const i18n = editorConfig.i18n || {};

const escapeHtml = (value = '') => value
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#039;');

const inlineMarkdownToHtml = (value = '') => {
    let output = escapeHtml(value);

    output = output.replace(/`([^`]+)`/g, '<code>$1</code>');
    output = output.replace(/\*\*([^*]+)\*\*/g, '<strong>$1</strong>');
    output = output.replace(/~~([^~]+)~~/g, '<s>$1</s>');
    output = output.replace(/\*([^*]+)\*/g, '<em>$1</em>');
    output = output.replace(/\[([^\]]+)\]\((https?:\/\/[^\s)]+)\)/g, '<a href="$2">$1</a>');

    return output;
};

const htmlToInlineMarkdown = (value = '') => {
    if (typeof document === 'undefined') {
        return value;
    }

    const container = document.createElement('div');
    container.innerHTML = value;

    const visit = (node) => {
        if (node.nodeType === Node.TEXT_NODE) {
            return node.textContent || '';
        }

        if (node.nodeType !== Node.ELEMENT_NODE) {
            return '';
        }

        const content = Array.from(node.childNodes).map(visit).join('');

        switch (node.tagName) {
        case 'BR':
            return '\n';
        case 'STRONG':
        case 'B':
            return `**${content}**`;
        case 'EM':
        case 'I':
            return `*${content}*`;
        case 'S':
        case 'DEL':
            return `~~${content}~~`;
        case 'CODE':
            return `\`${content}\``;
        case 'A': {
            const href = node.getAttribute('href') || '';

            return href ? `[${content}](${href})` : content;
        }
        case 'DIV':
        case 'P':
            return `${content}\n`;
        default:
            return content;
        }
    };

    return Array.from(container.childNodes)
        .map(visit)
        .join('')
        .replace(/\u00a0/g, ' ')
        .replace(/\n{3,}/g, '\n\n')
        .trim();
};

const normalizeMarkdown = (value = '') => String(value).replace(/\r\n/g, '\n');
const normalizeBlockText = (value = '') => String(value).replace(/<br\s*\/?>/gi, '\n').replace(/&nbsp;/gi, ' ').trim();
const normalizeEditorJsText = (value) => normalizeBlockText(typeof value === 'string' ? value : '');

class SafeHeader extends Header {
    static get toolbox() {
        return Header.toolbox;
    }

    static get pasteConfig() {
        return Header.pasteConfig;
    }

    static get conversionConfig() {
        return Header.conversionConfig;
    }

    static get sanitize() {
        return Header.sanitize;
    }

    static get isReadOnlySupported() {
        return Header.isReadOnlySupported;
    }

    validate(savedData) {
        return normalizeEditorJsText(savedData?.text) !== '';
    }
}

const isBlank = (line = '') => line.trim() === '';
const isCodeFence = (line = '') => /^```/.test(line.trim());
const isHeading = (line = '') => /^(#{1,3})\s+/.test(line.trim());
const isQuote = (line = '') => /^>\s?/.test(line.trim());
const isDelimiter = (line = '') => /^(-{3,}|\*{3,}|_{3,})$/.test(line.trim());
const isChecklistItem = (line = '') => /^[-*+]\s+\[( |x|X)\]\s+/.test(line.trim());
const isOrderedListItem = (line = '') => /^\d+\.\s+/.test(line.trim());
const isUnorderedListItem = (line = '') => /^[-*+]\s+/.test(line.trim()) && !isChecklistItem(line);
const isListItem = (line = '') => isChecklistItem(line) || isOrderedListItem(line) || isUnorderedListItem(line);
const startsStandaloneBlock = (line = '') => isCodeFence(line) || isHeading(line) || isQuote(line) || isDelimiter(line) || isListItem(line);

const parseListBlock = (lines, startIndex) => {
    const firstLine = lines[startIndex] || '';
    const style = isChecklistItem(firstLine)
        ? 'checklist'
        : (isOrderedListItem(firstLine) ? 'ordered' : 'unordered');
    const items = [];
    let index = startIndex;

    while (index < lines.length) {
        const line = lines[index] || '';

        if (isBlank(line)) {
            break;
        }

        if (style === 'checklist' && !isChecklistItem(line)) {
            break;
        }

        if (style === 'ordered' && !isOrderedListItem(line)) {
            break;
        }

        if (style === 'unordered' && !isUnorderedListItem(line)) {
            break;
        }

        if (style === 'checklist') {
            const match = line.trim().match(/^[-*+]\s+\[( |x|X)\]\s+(.+)$/);

            items.push({
                content: inlineMarkdownToHtml(match?.[2] || ''),
                meta: {
                    checked: Boolean(match?.[1] && match[1].toLowerCase() === 'x'),
                },
                items: [],
            });
        } else if (style === 'ordered') {
            const match = line.trim().match(/^\d+\.\s+(.+)$/);

            items.push({
                content: inlineMarkdownToHtml(match?.[1] || ''),
                meta: {},
                items: [],
            });
        } else {
            const match = line.trim().match(/^[-*+]\s+(.+)$/);

            items.push({
                content: inlineMarkdownToHtml(match?.[1] || ''),
                meta: {},
                items: [],
            });
        }

        index += 1;
    }

    return {
        nextIndex: index,
        block: {
            type: 'list',
            data: {
                style,
                items,
            },
        },
    };
};

const markdownToBlocks = (markdown = '') => {
    const lines = normalizeMarkdown(markdown).split('\n');
    const blocks = [];
    let index = 0;

    while (index < lines.length) {
        const line = lines[index] || '';

        if (isBlank(line)) {
            index += 1;
            continue;
        }

        if (isCodeFence(line)) {
            const codeLines = [];
            index += 1;

            while (index < lines.length && !isCodeFence(lines[index])) {
                codeLines.push(lines[index]);
                index += 1;
            }

            if (index < lines.length && isCodeFence(lines[index])) {
                index += 1;
            }

            blocks.push({
                type: 'code',
                data: {
                    code: codeLines.join('\n'),
                },
            });
            continue;
        }

        if (isHeading(line)) {
            const match = line.trim().match(/^(#{1,3})\s+(.+)$/);

            blocks.push({
                type: 'header',
                data: {
                    level: match?.[1]?.length || 2,
                    text: inlineMarkdownToHtml(match?.[2] || ''),
                },
            });
            index += 1;
            continue;
        }

        if (isQuote(line)) {
            const quoteLines = [];

            while (index < lines.length && isQuote(lines[index])) {
                quoteLines.push((lines[index] || '').replace(/^>\s?/, ''));
                index += 1;
            }

            blocks.push({
                type: 'quote',
                data: {
                    text: inlineMarkdownToHtml(quoteLines.join('\n')).replace(/\n/g, '<br>'),
                    caption: '',
                    alignment: 'left',
                },
            });
            continue;
        }

        if (isDelimiter(line)) {
            blocks.push({
                type: 'delimiter',
                data: {},
            });
            index += 1;
            continue;
        }

        if (isListItem(line)) {
            const parsed = parseListBlock(lines, index);
            blocks.push(parsed.block);
            index = parsed.nextIndex;
            continue;
        }

        const paragraphLines = [];

        while (index < lines.length && !isBlank(lines[index]) && !startsStandaloneBlock(lines[index])) {
            paragraphLines.push(lines[index]);
            index += 1;
        }

        const paragraphText = inlineMarkdownToHtml(paragraphLines.join('\n')).replace(/\n/g, '<br>');

        if (normalizeBlockText(paragraphText) !== '') {
            blocks.push({
                type: 'paragraph',
                data: {
                    text: paragraphText,
                },
            });
        }
    }

    return blocks;
};

const renderListItemsToMarkdown = (items = [], style = 'unordered', depth = 0) => items
    .flatMap((item, index) => {
        const itemContent = typeof item === 'string'
            ? item
            : htmlToInlineMarkdown(item?.content || '');
        const normalizedContent = itemContent.replace(/\n+/g, ' ').trim();
        const indentation = '  '.repeat(depth);
        const prefix = style === 'ordered'
            ? `${index + 1}. `
            : (style === 'checklist'
                ? `- [${item?.meta?.checked ? 'x' : ' '}] `
                : '- ');
        const nestedItems = Array.isArray(item?.items) && item.items.length > 0
            ? renderListItemsToMarkdown(item.items, style, depth + 1)
            : '';

        return [
            `${indentation}${prefix}${normalizedContent}`.trimEnd(),
            nestedItems,
        ].filter(Boolean);
    })
    .join('\n');

const blocksToMarkdown = (outputData) => {
    const blocks = outputData?.blocks || [];
    const segments = blocks.map((block) => {
        const data = block?.data || {};

        switch (block?.type) {
        case 'header':
            return `${'#'.repeat(Number(data.level) || 2)} ${htmlToInlineMarkdown(data.text || '').trim()}`.trim();
        case 'paragraph':
            return htmlToInlineMarkdown(data.text || '').trim();
        case 'quote': {
            const quoteText = htmlToInlineMarkdown(data.text || '')
                .split('\n')
                .filter(Boolean)
                .map((line) => `> ${line}`)
                .join('\n');

            return quoteText;
        }
        case 'code':
            return `\`\`\`\n${data.code || ''}\n\`\`\``;
        case 'delimiter':
            return '---';
        case 'list':
            return renderListItemsToMarkdown(data.items || [], data.style || 'unordered');
        default:
            return htmlToInlineMarkdown(data.text || '').trim();
        }
    }).filter((segment) => segment !== '');

    return segments.join('\n\n').trim();
};

if (window.axios && csrfToken) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
}

if (target && editorJsHolder) {
    let editorJs = null;
    let initialTitleValue = titleInput?.value || '';
    let initialContentValue = target.value || '';
    let editorContentDirty = false;
    let currentServerUpdatedAt = editorConfig.lastKnownUpdatedAt || null;
    let latestServerSnapshot = null;
    let saveTimer = null;
    let statusTimer = null;
    let isSaving = false;
    let hasPendingSave = false;
    let suppressChangeHandling = false;

    const syncTextareaValue = (value) => {
        target.value = value;
    };

    const updateDirtyIndicators = (isDirty) => {
        if (unsavedIndicator) {
            unsavedIndicator.classList.toggle('hidden', !isDirty);
        }

        document.title = isDirty ? `* ${originalDocumentTitle}` : originalDocumentTitle;
    };

    const refreshDirtyState = () => {
        const isDirty = (titleInput?.value || '') !== initialTitleValue || editorContentDirty;

        updateDirtyIndicators(isDirty);

        return isDirty;
    };

    const setStatus = (message, tone = 'neutral') => {
        if (!statusElement) {
            return;
        }

        statusElement.textContent = message;
        statusElement.classList.remove('hidden', 'bg-slate-900', 'bg-emerald-600', 'bg-rose-600');

        if (tone === 'success') {
            statusElement.classList.add('bg-emerald-600');
        } else if (tone === 'error') {
            statusElement.classList.add('bg-rose-600');
        } else {
            statusElement.classList.add('bg-slate-900');
        }

        clearTimeout(statusTimer);
        statusTimer = setTimeout(() => statusElement.classList.add('hidden'), 1800);
    };

    const logAutosaveError = (error, context = {}) => {
        const statusCode = error?.response?.status || 'NETWORK';
        const serverMessage = error?.response?.data?.message;
        const validationErrors = error?.response?.data?.errors;

        console.warn('[catat-autosave]', {
            statusCode,
            serverMessage,
            validationErrors,
            ...context,
        });
    };

    const fetchLatestServerSnapshot = async () => {
        if (!editorConfig.fetchUrl || !window.axios) {
            return null;
        }

        const response = await window.axios.get(editorConfig.fetchUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            timeout: 10000,
        });

        return response?.data || null;
    };

    const setConflictState = (isConflict, message = i18n.conflictIndicator || 'Version conflict') => {
        if (!conflictIndicator) {
            return;
        }

        conflictIndicator.textContent = message;
        conflictIndicator.classList.toggle('hidden', !isConflict);

        if (conflictReloadButton) {
            conflictReloadButton.classList.toggle('hidden', !isConflict);
        }

        if (!isConflict && conflictComparePanel) {
            conflictComparePanel.classList.add('hidden');
        }
    };

    const getMarkdownFromEditor = async () => {
        if (!editorJs) {
            return target.value || '';
        }

        const output = await editorJs.save();
        return blocksToMarkdown(output);
    };

    const hasPendingTransientBlock = async () => {
        if (!editorJs) {
            return false;
        }

        const blockCount = editorJs.blocks.getBlocksCount();

        for (let index = 0; index < blockCount; index += 1) {
            const block = editorJs.blocks.getBlockByIndex(index);

            if (!block || !['header', 'paragraph'].includes(block.name)) {
                continue;
            }

            const savedData = await block.save();
            const blockText = block.holder?.textContent?.replace(/\u00a0/g, ' ').trim() || '';
            const savedText = normalizeEditorJsText(savedData?.text);
            const isValid = savedText !== '';
            const isSlashCommand = block.name === 'paragraph' && /^\/\S*$/.test(blockText);

            if (!isValid || isSlashCommand) {
                return true;
            }
        }

        return false;
    };

    const getCurrentContent = async () => {
        const markdown = await getMarkdownFromEditor();
        syncTextareaValue(markdown);

        return markdown;
    };

    const renderConflictComparePanel = async () => {
        if (!conflictComparePanel || !latestServerSnapshot) {
            return;
        }

        if (conflictLocalTitle) {
            conflictLocalTitle.value = (titleInput?.value || '').trim();
        }

        if (conflictLocalContent) {
            conflictLocalContent.value = await getCurrentContent();
        }

        if (conflictServerTitle) {
            conflictServerTitle.value = latestServerSnapshot.title || '';
        }

        if (conflictServerContent) {
            conflictServerContent.value = latestServerSnapshot.content_md || '';
        }

        conflictComparePanel.classList.remove('hidden');
    };

    const scheduleSave = () => {
        if (suppressChangeHandling) {
            return;
        }

        refreshDirtyState();
        clearTimeout(saveTimer);
        setStatus(i18n.statusTyping || 'Mengetik...');
        saveTimer = setTimeout(() => {
            saveNote();
        }, 900);
    };

    const handleEditorMutation = (event) => {
        if (suppressChangeHandling) {
            return;
        }

        editorContentDirty = true;
        refreshDirtyState();

        const mutationType = event?.type || event?.detail?.type || '';

        if (mutationType === 'Added' || mutationType === 'Removed' || mutationType === 'Moved') {
            clearTimeout(saveTimer);
            setStatus(i18n.statusTyping || 'Mengetik...');
            return;
        }

        scheduleSave();
    };

    const ensureEditor = async (markdown) => {
        if (!editorJs) {
            editorJs = new EditorJS({
                holder: editorJsHolder,
                minHeight: 320,
                inlineToolbar: ['link', 'bold', 'italic'],
                placeholder: 'Tulis catatan di sini...',
                tools: {
                    header: {
                        class: SafeHeader,
                        inlineToolbar: ['link', 'bold', 'italic'],
                        config: {
                            placeholder: 'Heading',
                            levels: [1, 2, 3],
                            defaultLevel: 2,
                        },
                    },
                    list: List,
                    quote: Quote,
                    code: CodeTool,
                    delimiter: Delimiter,
                },
                data: {
                    blocks: markdownToBlocks(markdown),
                },
                onChange: (_api, event) => {
                    handleEditorMutation(event);
                },
            });

            await editorJs.isReady;
            return;
        }

        await editorJs.isReady;
        await editorJs.render({
            blocks: markdownToBlocks(markdown),
        });
    };

    const saveNote = async (retryCount = 0) => {
        if (!editorConfig.saveUrl || !window.axios) {
            return;
        }

        if (isSaving) {
            hasPendingSave = true;
            return;
        }

        if (await hasPendingTransientBlock()) {
            clearTimeout(saveTimer);
            setStatus(i18n.statusTyping || 'Mengetik...');
            return;
        }

        const currentTitle = (titleInput?.value || editorConfig.currentTitle || '').trim();
        const currentContent = editorContentDirty ? await getCurrentContent() : initialContentValue;
        const hasChanges = currentTitle !== initialTitleValue || currentContent !== initialContentValue;

        if (!hasChanges) {
            return;
        }

        if (currentTitle === '') {
            setStatus(i18n.statusTitleRequired || 'Judul wajib diisi', 'error');
            return;
        }

        isSaving = true;
        setStatus(i18n.statusSaving || 'Menyimpan...');

        try {
            const response = await window.axios.put(editorConfig.saveUrl, {
                content_md: currentContent,
                title: currentTitle,
                last_known_updated_at: currentServerUpdatedAt,
                _token: csrfToken,
            }, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                timeout: 15000,
            });

            initialTitleValue = titleInput?.value || initialTitleValue;
            initialContentValue = currentContent;
            editorContentDirty = false;
            currentServerUpdatedAt = response?.data?.updated_at || currentServerUpdatedAt;
            setConflictState(false);
            refreshDirtyState();
            setStatus(i18n.statusSaved || 'Tersimpan', 'success');
        } catch (error) {
            const isNetworkIssue = !error?.response;
            const isValidationIssue = error?.response?.status === 422;
            const isConflictIssue = error?.response?.status === 409;
            const validationMessage = Object.values(error?.response?.data?.errors || {}).flat()[0];

            logAutosaveError(error, {
                saveUrl: editorConfig.saveUrl,
                nodeTitle: currentTitle,
                hasPendingSave,
                editor: 'editorjs',
            });

            if (isNetworkIssue && retryCount < 1) {
                setStatus(i18n.statusNetworkRetry || 'Koneksi lambat, mencoba lagi...');
                setTimeout(() => saveNote(retryCount + 1), 500);
                return;
            }

            if (isValidationIssue) {
                setStatus(validationMessage || i18n.statusInvalidData || 'Data belum valid', 'error');
                return;
            }

            if (isConflictIssue) {
                currentServerUpdatedAt = error?.response?.data?.current_updated_at || currentServerUpdatedAt;
                setConflictState(true, i18n.conflictIndicator || 'Version conflict');
                setStatus(error?.response?.data?.message || i18n.statusConflictRetry || 'Dokumen berubah, simpan ulang', 'error');

                try {
                    latestServerSnapshot = await fetchLatestServerSnapshot();
                    await renderConflictComparePanel();
                } catch (snapshotError) {
                    logAutosaveError(snapshotError, {
                        saveUrl: editorConfig.saveUrl,
                        fetchUrl: editorConfig.fetchUrl,
                        action: 'fetch-latest-snapshot',
                    });
                }

                return;
            }

            setStatus(i18n.statusFailed || 'Gagal menyimpan', 'error');
        } finally {
            isSaving = false;

            if (hasPendingSave) {
                hasPendingSave = false;
                saveNote();
            }
        }
    };

    if (titleInput) {
        titleInput.addEventListener('input', () => {
            refreshDirtyState();
            scheduleSave();
        });
    }

    document.addEventListener('keydown', (event) => {
        const isSaveShortcut = (event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 's';

        if (!isSaveShortcut) {
            return;
        }

        event.preventDefault();
        clearTimeout(saveTimer);
        setStatus(i18n.statusSaving || 'Menyimpan...');
        saveNote();
    });

    if (editorForm) {
        editorForm.addEventListener('submit', (event) => {
            event.preventDefault();
            clearTimeout(saveTimer);
            setStatus(i18n.statusSaving || 'Menyimpan...');
            saveNote();
        });
    }

    if (conflictReloadButton) {
        conflictReloadButton.addEventListener('click', () => {
            window.location.reload();
        });
    }

    if (conflictApplyLatestButton) {
        conflictApplyLatestButton.addEventListener('click', async () => {
            if (!latestServerSnapshot) {
                return;
            }

            suppressChangeHandling = true;

            try {
                if (titleInput) {
                    titleInput.value = latestServerSnapshot.title || '';
                }

                syncTextareaValue(latestServerSnapshot.content_md || '');
                await ensureEditor(latestServerSnapshot.content_md || '');

                initialTitleValue = titleInput?.value || '';
                initialContentValue = latestServerSnapshot.content_md || '';
                editorContentDirty = false;
                currentServerUpdatedAt = latestServerSnapshot.updated_at || currentServerUpdatedAt;
                setConflictState(false);
                refreshDirtyState();
                setStatus(i18n.statusLatestApplied || 'Versi terbaru diterapkan', 'success');
            } finally {
                suppressChangeHandling = false;
            }
        });
    }

    if (conflictClosePanelButton) {
        conflictClosePanelButton.addEventListener('click', () => {
            if (conflictComparePanel) {
                conflictComparePanel.classList.add('hidden');
            }
        });
    }

    syncTextareaValue(initialContentValue);
    ensureEditor(initialContentValue)
        .then(() => {
            editorContentDirty = false;
            refreshDirtyState();
        })
        .catch((error) => {
            logAutosaveError(error, {
                action: 'initialize-editor',
            });
            setStatus(i18n.statusFailed || 'Gagal memuat editor', 'error');
        });
}
