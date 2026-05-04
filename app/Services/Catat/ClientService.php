<?php

declare(strict_types=1);

namespace App\Services\Catat;

use App\Models\Catat\Client;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ClientService
{
    public function create(array $data): Client
    {
        return DB::transaction(function () use ($data): Client {
            $data['created_by'] = $data['created_by'] ?? $this->resolveActorId();

            return Client::create($data);
        });
    }

    public function update(Client $client, array $data): Client
    {
        return DB::transaction(function () use ($client, $data): Client {
            $client->update($data);

            return $client->refresh();
        });
    }

    public function delete(Client $client): bool
    {
        return (bool) DB::transaction(fn (): bool => $client->delete());
    }

    public function getWithWorkspaceStats(): Collection
    {
        return Client::query()
            ->withCount([
                'workspaces',
                'workspaces as workspaces_open_count' => fn ($query) => $query->where('status', 'open'),
                'workspaces as workspaces_ongoing_count' => fn ($query) => $query->where('status', 'ongoing'),
                'workspaces as workspaces_closed_count' => fn ($query) => $query->where('status', 'closed'),
            ])
            ->orderBy('name')
            ->get();
    }

    private function resolveActorId(): string
    {
        $actorId = Auth::id();

        if ($actorId === null) {
            throw new RuntimeException('User login diperlukan untuk membuat client.');
        }

        return (string) $actorId;
    }
}
