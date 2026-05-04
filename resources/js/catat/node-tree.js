const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

if (window.axios && csrfToken) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
}

function initSortable() {
    if (typeof window.Sortable === 'undefined') {
        return;
    }

    document.querySelectorAll('.js-node-children').forEach((container) => {
        if (container.dataset.sortableReady === '1') {
            return;
        }

        container.dataset.sortableReady = '1';
        new window.Sortable(container, {
            group: 'catat-nodes',
            animation: 150,
            draggable: '.js-node-item',
            handle: '.js-drag-handle',
            onEnd: async (event) => {
                const moveUrl = event.item?.dataset?.moveUrl;

                if (!moveUrl || !window.axios) {
                    return;
                }

                const parentContainer = event.to.closest('.js-node-children');
                const newParentId = parentContainer?.dataset?.parentId || null;
                const sortOrder = Number(event.newIndex ?? 0);

                try {
                    await window.axios.post(moveUrl, {
                        new_parent_id: newParentId,
                        sort_order: sortOrder,
                    }, {
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });
                } catch (error) {
                    const message = error?.response?.data?.message
                        || Object.values(error?.response?.data?.errors || {}).flat()[0]
                        || 'Node gagal dipindahkan. Halaman akan dimuat ulang.';

                    window.alert(message);
                    window.location.reload();
                }
            },
        });
    });
}

document.addEventListener('DOMContentLoaded', initSortable);
