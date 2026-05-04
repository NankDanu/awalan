<?php

declare(strict_types=1);

namespace App\Policies\Catat;

use App\Models\Catat\Workspace;
use App\Models\User;

class WorkspacePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('catat.workspaces.view');
    }

    public function view(User $user, Workspace $workspace): bool
    {
        return $user->can('catat.workspaces.view');
    }

    public function create(User $user): bool
    {
        return $user->can('catat.workspaces.create');
    }

    public function update(User $user, Workspace $workspace): bool
    {
        return $user->can('catat.workspaces.edit');
    }

    public function delete(User $user, Workspace $workspace): bool
    {
        return $user->can('catat.workspaces.delete');
    }

    public function archive(User $user, Workspace $workspace): bool
    {
        return $user->can('catat.workspaces.archive');
    }

    public function restore(User $user, Workspace $workspace): bool
    {
        return $user->can('catat.workspaces.delete');
    }

    public function forceDelete(User $user, Workspace $workspace): bool
    {
        return $user->can('catat.workspaces.delete');
    }
}
