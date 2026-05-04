<?php

declare(strict_types=1);

namespace App\Policies\Catat;

use App\Models\Catat\Client;
use App\Models\User;

class ClientPolicy
{
    /**
     * Determine whether the user can view any clients.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('catat.clients.view');
    }

    /**
     * Determine whether the user can view the client.
     */
    public function view(User $user, Client $client): bool
    {
        return $user->can('catat.clients.view');
    }

    /**
     * Determine whether the user can create clients.
     */
    public function create(User $user): bool
    {
        return $user->can('catat.clients.create');
    }

    /**
     * Determine whether the user can update the client.
     */
    public function update(User $user, Client $client): bool
    {
        return $user->can('catat.clients.edit');
    }

    /**
     * Determine whether the user can delete the client.
     */
    public function delete(User $user, Client $client): bool
    {
        return $user->can('catat.clients.delete');
    }

    /**
     * Determine whether the user can restore the client.
     */
    public function restore(User $user, Client $client): bool
    {
        return $user->can('catat.clients.delete');
    }

    /**
     * Determine whether the user can permanently delete the client.
     */
    public function forceDelete(User $user, Client $client): bool
    {
        return $user->can('catat.clients.delete');
    }
}
