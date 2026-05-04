<?php

declare(strict_types=1);

namespace Database\Seeders\Catat;

use App\Models\Catat\Client;
use App\Models\Catat\Node;
use App\Models\Catat\Workspace;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CtWorkspaceSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = ['open', 'ongoing', 'closed'];
        $types = ['one-time', 'maintenance', 'saas', 'retainer'];

        Client::query()->get()->each(function (Client $client) use ($statuses, $types): void {
            for ($index = 1; $index <= 2; $index++) {
                $wsName = sprintf('%s Workspace %d', $client->company ?? $client->name, $index);
                $workspace = Workspace::updateOrCreate(
                    ['slug' => Str::slug($wsName)],
                    [
                        'client_id' => $client->id,
                        'name' => $wsName,
                        'is_project' => $index === 1,
                        'status' => $statuses[($index - 1) % count($statuses)],
                        'type_tag' => $index === 1 ? $types[0] : null,
                        'description' => 'Workspace sample untuk modul Catat.',
                        'created_by' => $client->created_by,
                        'is_archived' => false,
                        'archived_at' => null,
                    ]
                );

                Node::updateOrCreate(
                    [
                        'workspace_id' => $workspace->id,
                        'title' => 'Notes',
                        'type' => 'folder',
                        'parent_id' => null,
                    ],
                    [
                        'sort_order' => 0,
                        'created_by' => $workspace->created_by,
                        'updated_by' => null,
                    ]
                );

                Node::updateOrCreate(
                    [
                        'workspace_id' => $workspace->id,
                        'title' => 'Summary',
                        'type' => 'note',
                        'parent_id' => null,
                    ],
                    [
                        'content_md' => "# {$workspace->name}\n\n## Deskripsi\nWorkspace ini dibuat sebagai ruang dokumentasi untuk mencatat aktivitas, referensi, dan catatan penting terkait project.\n\n## Tujuan\n- Dokumentasi alur kerja dan keputusan teknis\n- Catatan meeting dan progress update\n- Referensi dan link penting\n\n## Catatan\nGunakan folder **Notes** untuk menambahkan catatan baru.",
                        'sort_order' => 1,
                        'created_by' => $workspace->created_by,
                        'updated_by' => $workspace->created_by,
                    ]
                );

                
            }
        });
    }
}