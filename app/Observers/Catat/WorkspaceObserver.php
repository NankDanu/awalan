<?php

declare(strict_types=1);

namespace App\Observers\Catat;

use App\Models\Catat\Node;
use App\Models\Catat\Workspace;

class WorkspaceObserver
{
    public function created(Workspace $workspace): void
    {
        Node::create([
            'workspace_id' => $workspace->id,
            'parent_id'    => null,
            'type'         => 'folder',
            'title'        => 'Notes',
            'content_md'   => null,
            'sort_order'   => 0,
            'created_by'   => $workspace->created_by,
            'updated_by'   => null,
        ]);

        Node::create([
            'workspace_id' => $workspace->id,
            'parent_id'    => null,
            'type'         => 'note',
            'title'        => 'Summary',
            'content_md'   => "# {$workspace->name}\n\n## Deskripsi\nWorkspace ini dibuat sebagai ruang dokumentasi untuk mencatat aktivitas, referensi, dan catatan penting terkait project.\n\n## Tujuan\n- Dokumentasi alur kerja dan keputusan teknis\n- Catatan meeting dan progress update\n- Referensi dan link penting\n\n## Catatan\nGunakan folder **Notes** untuk menambahkan catatan baru.",
            'sort_order'   => 1,
            'created_by'   => $workspace->created_by,
            'updated_by'   => null,
        ]);
    }

    public function updated(Workspace $workspace): void
    {
        if (! $workspace->wasChanged('status')) {
            return;
        }

        if ($workspace->status === 'closed' && ! $workspace->is_archived) {
            $workspace->is_archived = true;
            $workspace->archived_at = now();
            $workspace->saveQuietly();

            return;
        }

        if ($workspace->status !== 'closed' && $workspace->is_archived) {
            $workspace->is_archived = false;
            $workspace->archived_at = null;
            $workspace->saveQuietly();
        }
    }
}
