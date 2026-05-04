<?php

declare(strict_types=1);

namespace App\Services\Catat;

use App\Models\Catat\Workspace;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class WorkspaceService
{
    public function create(array $data): Workspace
    {
        return DB::transaction(function () use ($data): Workspace {
            $actorId = $data['created_by'] ?? $this->resolveActorId();
            $data['created_by'] = $actorId;
            $data['slug'] = $data['slug'] ?? $this->generateUniqueSlug((string) $data['name']);

            $workspace = Workspace::create($data);

            return $workspace;
        });
    }

    public function update(Workspace $workspace, array $data): Workspace
    {
        return DB::transaction(function () use ($workspace, $data): Workspace {
            $workspace->update($data);

            return $workspace->refresh();
        });
    }

    public function archive(Workspace $workspace): Workspace
    {
        return DB::transaction(function () use ($workspace): Workspace {
            $workspace->update([
                'status' => 'closed',
                'is_archived' => true,
                'archived_at' => now(),
            ]);

            return $workspace->refresh();
        });
    }

    public function unarchive(Workspace $workspace, string $newStatus = 'open'): Workspace
    {
        return DB::transaction(function () use ($workspace, $newStatus): Workspace {
            $workspace->update([
                'status' => $newStatus,
                'is_archived' => false,
                'archived_at' => null,
            ]);

            return $workspace->refresh();
        });
    }

    public function promoteToProject(Workspace $workspace): Workspace
    {
        return DB::transaction(function () use ($workspace): Workspace {
            $workspace->update(['is_project' => true]);

            return $workspace->refresh();
        });
    }

    public function demoteFromProject(Workspace $workspace): Workspace
    {
        return DB::transaction(function () use ($workspace): Workspace {
            $workspace->update([
                'is_project' => false,
                'type_tag' => null,
            ]);

            return $workspace->refresh();
        });
    }

    public function getActive(): Collection
    {
        return Workspace::query()
            ->where('is_archived', false)
            ->with('client')
            ->orderBy('name')
            ->get();
    }

    public function getOngoing(): Collection
    {
        return Workspace::query()
            ->where('is_archived', false)
            ->where('status', 'ongoing')
            ->withCount('nodes')
            ->with('client')
            ->orderBy('name')
            ->get();
    }

    public function getOpen(): Collection
    {
        return Workspace::query()
            ->where('is_archived', false)
            ->where('status', 'open')
            ->with('client')
            ->orderBy('name')
            ->get();
    }

    public function getArchived(): Collection
    {
        return Workspace::query()
            ->where('is_archived', true)
            ->with('client')
            ->latest('archived_at')
            ->get();
    }

    private function resolveActorId(): string
    {
        $actorId = Auth::id();

        if ($actorId === null) {
            throw new RuntimeException('User login diperlukan untuk membuat workspace.');
        }

        return (string) $actorId;
    }

    private function generateUniqueSlug(string $name): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $suffix = 1;

        while (Workspace::query()->where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $suffix;
            $suffix++;
        }

        return $slug;
    }
}
