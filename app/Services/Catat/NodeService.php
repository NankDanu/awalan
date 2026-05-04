<?php

declare(strict_types=1);

namespace App\Services\Catat;

use App\Models\Catat\Node;
use App\Models\Catat\Workspace;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class NodeService
{
    public function getLinksByType(Workspace $workspace, string $linkType): Collection
    {
        return Node::query()
            ->where('workspace_id', $workspace->id)
            ->where('type', 'link')
            ->where('link_type', $linkType)
            ->orderBy('sort_order')
            ->get();
    }

    public function getTree(Workspace $workspace): Collection
    {
        $flatNodes = DB::select(
            "
            WITH RECURSIVE node_tree AS (
              SELECT *, 0 as depth
              FROM ct_nodes
              WHERE workspace_id = :workspace_id AND parent_id IS NULL AND deleted_at IS NULL
              UNION ALL
              SELECT n.*, nt.depth + 1
              FROM ct_nodes n
              INNER JOIN node_tree nt ON n.parent_id = nt.id
              WHERE n.deleted_at IS NULL
            )
            SELECT * FROM node_tree ORDER BY depth, sort_order
            ",
            ['workspace_id' => $workspace->id]
        );

        return collect($this->buildTree(array_map(static fn ($row) => (array) $row, $flatNodes)));
    }

    public function createFolder(Workspace $workspace, ?string $parentId, string $title): Node
    {
        return $this->createNode($workspace, $parentId, 'folder', $title, null);
    }

    public function createNote(Workspace $workspace, ?string $parentId, string $title): Node
    {
        return $this->createNode($workspace, $parentId, 'note', $title, '');
    }

    public function createLink(Workspace $workspace, ?string $parentId, string $title, string $url, string $linkType, ?string $environment = null): Node
    {
        return DB::transaction(function () use ($workspace, $parentId, $title, $url, $linkType, $environment): Node {
            $this->assertCreateTargetIsValid($workspace, $parentId);

            $lastSortOrder = Node::query()
                ->where('workspace_id', $workspace->id)
                ->where('parent_id', $parentId)
                ->max('sort_order');

            $nextSortOrder = $lastSortOrder === null ? 0 : ((int) $lastSortOrder + 1);

            return Node::create([
                'workspace_id' => $workspace->id,
                'parent_id' => $parentId,
                'type' => 'link',
                'title' => $title,
                'url' => $url,
                'link_type' => $linkType,
                'environment' => $environment,
                'sort_order' => $nextSortOrder,
                'created_by' => $this->resolveActorId(),
                'updated_by' => null,
            ]);
        });
    }

    public function updateLink(Node $node, string $title, string $url, string $linkType, ?string $environment, ?string $linkNotes): Node
    {
        return DB::transaction(function () use ($node, $title, $url, $linkType, $environment, $linkNotes): Node {
            $node->update([
                'title'       => $title,
                'url'         => $url,
                'link_type'   => $linkType,
                'environment' => $environment,
                'link_notes'  => $linkNotes,
                'updated_by'  => $this->resolveActorId(),
            ]);

            return $node->refresh();
        });
    }

    public function updateNote(Node $node, string $title, string $contentMd): Node
    {
        return DB::transaction(function () use ($node, $title, $contentMd): Node {
            $node->update([
                'title' => $title,
                'content_md' => $contentMd,
                'updated_by' => $this->resolveActorId(),
            ]);

            return $node->refresh();
        });
    }

    public function move(Node $node, ?string $newParentId, int $sortOrder): Node
    {
        return DB::transaction(function () use ($node, $newParentId, $sortOrder): Node {
            $this->assertMoveTargetIsValid($node, $newParentId);

            $node->update([
                'parent_id' => $newParentId,
                'sort_order' => $sortOrder,
                'updated_by' => $this->resolveActorId(),
            ]);

            return $node->refresh();
        });
    }

    public function reorder(array $orderedIds): void
    {
        if ($orderedIds === []) {
            return;
        }

        DB::transaction(function () use ($orderedIds): void {
            $cases = [];
            $bindings = [];

            foreach ($orderedIds as $index => $id) {
                $cases[] = 'WHEN ? THEN ?';
                $bindings[] = (string) $id;
                $bindings[] = $index;
            }

            $placeholders = implode(',', array_fill(0, count($orderedIds), '?'));
            $bindings = array_merge($bindings, array_map(static fn ($id) => (string) $id, $orderedIds));

            DB::update(
                "UPDATE ct_nodes SET sort_order = CASE id " . implode(' ', $cases) . " END WHERE id IN ({$placeholders})",
                $bindings
            );
        });
    }

    public function delete(Node $node): bool
    {
        return (bool) DB::transaction(function () use ($node): bool {
            $descendants = DB::select(
                "
                WITH RECURSIVE node_descendants AS (
                    SELECT id
                    FROM ct_nodes
                    WHERE id = :node_id AND deleted_at IS NULL
                    UNION ALL
                    SELECT n.id
                    FROM ct_nodes n
                    INNER JOIN node_descendants nd ON n.parent_id = nd.id
                    WHERE n.deleted_at IS NULL
                )
                SELECT id FROM node_descendants
                ",
                ['node_id' => $node->id]
            );

            $ids = collect($descendants)
                ->map(static fn ($row) => $row->id)
                ->values()
                ->all();

            if ($ids === []) {
                return false;
            }

            return Node::query()->whereIn('id', $ids)->delete() > 0;
        });
    }

    public function buildTree(array $flatNodes, ?string $parentId = null): array
    {
        $branches = [];

        foreach ($flatNodes as $node) {
            if (($node['parent_id'] ?? null) !== $parentId) {
                continue;
            }

            $node['children'] = $this->buildTree($flatNodes, (string) $node['id']);
            $branches[] = $node;
        }

        usort($branches, static function (array $left, array $right): int {
            return ((int) ($left['sort_order'] ?? 0)) <=> ((int) ($right['sort_order'] ?? 0));
        });

        return $branches;
    }

    private function createNode(Workspace $workspace, ?string $parentId, string $type, string $title, ?string $contentMd): Node
    {
        return DB::transaction(function () use ($workspace, $parentId, $type, $title, $contentMd): Node {
            $this->assertCreateTargetIsValid($workspace, $parentId);

            $lastSortOrder = Node::query()
                ->where('workspace_id', $workspace->id)
                ->where('parent_id', $parentId)
                ->max('sort_order');

            $nextSortOrder = $lastSortOrder === null ? 0 : ((int) $lastSortOrder + 1);

            return Node::create([
                'workspace_id' => $workspace->id,
                'parent_id' => $parentId,
                'type' => $type,
                'title' => $title,
                'content_md' => $contentMd,
                'sort_order' => $nextSortOrder,
                'created_by' => $this->resolveActorId(),
                'updated_by' => null,
            ]);
        });
    }

    private function assertCreateTargetIsValid(Workspace $workspace, ?string $parentId): void
    {
        if ($parentId === null) {
            return;
        }

        $parent = Node::query()
            ->select(['id', 'workspace_id', 'type'])
            ->find($parentId);

        if ($parent === null || $parent->workspace_id !== $workspace->id) {
            throw ValidationException::withMessages([
                'parent_id' => 'Folder parent tidak valid.',
            ]);
        }

        if (! $parent->isFolder()) {
            throw ValidationException::withMessages([
                'parent_id' => 'Dokumen hanya bisa dibuat di dalam folder.',
            ]);
        }

        $newDepth = $this->resolveNodeDepth((string) $parent->id) + 1;

        if ($newDepth > $this->resolveMaxNodeDepth()) {
            throw ValidationException::withMessages([
                'parent_id' => 'Maksimal kedalaman node adalah 5 level.',
            ]);
        }
    }

    private function assertMoveTargetIsValid(Node $node, ?string $newParentId): void
    {
        if ($newParentId === null) {
            return;
        }

        if ($node->id === $newParentId) {
            throw ValidationException::withMessages([
                'new_parent_id' => 'Node tidak bisa dipindahkan ke dirinya sendiri.',
            ]);
        }

        $newParent = Node::query()
            ->select(['id', 'workspace_id', 'type'])
            ->find($newParentId);

        if ($newParent === null || $newParent->workspace_id !== $node->workspace_id) {
            throw ValidationException::withMessages([
                'new_parent_id' => 'Parent tujuan tidak valid.',
            ]);
        }

        if (! $newParent->isFolder()) {
            throw ValidationException::withMessages([
                'new_parent_id' => 'Node hanya bisa dipindahkan ke folder.',
            ]);
        }

        if ($this->isDescendantOfNode($newParentId, $node->id)) {
            throw ValidationException::withMessages([
                'new_parent_id' => 'Node tidak bisa dipindahkan ke dalam turunannya sendiri.',
            ]);
        }

        $targetDepth = $this->resolveNodeDepth((string) $newParent->id) + 1;
        $subTreeDepth = $this->resolveSubTreeDepth($node->id);

        if (($targetDepth + $subTreeDepth) > $this->resolveMaxNodeDepth()) {
            throw ValidationException::withMessages([
                'new_parent_id' => 'Maksimal kedalaman node adalah 5 level.',
            ]);
        }
    }

    private function resolveNodeDepth(string $nodeId): int
    {
        $depth = 0;
        $currentParentId = Node::query()->whereKey($nodeId)->value('parent_id');

        while ($currentParentId !== null) {
            $depth++;
            $currentParentId = Node::query()->whereKey($currentParentId)->value('parent_id');
        }

        return $depth;
    }

    private function resolveSubTreeDepth(string $nodeId): int
    {
        $rows = DB::select(
            "
            WITH RECURSIVE node_descendants AS (
              SELECT id, parent_id, 0 AS depth
              FROM ct_nodes
              WHERE id = :node_id AND deleted_at IS NULL
              UNION ALL
              SELECT n.id, n.parent_id, nd.depth + 1
              FROM ct_nodes n
              INNER JOIN node_descendants nd ON n.parent_id = nd.id
              WHERE n.deleted_at IS NULL
            )
            SELECT MAX(depth) AS max_depth
            FROM node_descendants
            ",
            ['node_id' => $nodeId]
        );

        return (int) ($rows[0]->max_depth ?? 0);
    }

    private function isDescendantOfNode(string $candidateId, string $ancestorId): bool
    {
        $rows = DB::select(
            "
            WITH RECURSIVE node_descendants AS (
              SELECT id
              FROM ct_nodes
              WHERE id = :ancestor_id AND deleted_at IS NULL
              UNION ALL
              SELECT n.id
              FROM ct_nodes n
              INNER JOIN node_descendants nd ON n.parent_id = nd.id
              WHERE n.deleted_at IS NULL
            )
            SELECT id
            FROM node_descendants
            WHERE id = :candidate_id
            LIMIT 1
            ",
            [
                'ancestor_id' => $ancestorId,
                'candidate_id' => $candidateId,
            ]
        );

        return $rows !== [];
    }

    private function resolveMaxNodeDepth(): int
    {
        return max((int) config('catat.workspace.max_node_depth', 5), 0);
    }

    private function resolveActorId(): string
    {
        $actorId = Auth::id();

        if ($actorId === null) {
            throw new RuntimeException('User login diperlukan untuk mengelola node.');
        }

        return (string) $actorId;
    }
}
