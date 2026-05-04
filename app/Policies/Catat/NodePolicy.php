<?php

declare(strict_types=1);

namespace App\Policies\Catat;

use App\Models\Catat\Node;
use App\Models\User;

class NodePolicy
{
    /**
     * Determine whether the user can view any nodes.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('catat.nodes.view');
    }

    /**
     * Determine whether the user can view the node.
     */
    public function view(User $user, Node $node): bool
    {
        return $user->can('catat.nodes.view');
    }

    /**
     * Determine whether the user can create nodes.
     */
    public function create(User $user): bool
    {
        return $user->can('catat.nodes.create');
    }

    /**
     * Determine whether the user can update the node.
     */
    public function update(User $user, Node $node): bool
    {
        return $user->can('catat.nodes.edit');
    }

    /**
     * Determine whether the user can delete the node.
     */
    public function delete(User $user, Node $node): bool
    {
        return $user->can('catat.nodes.delete');
    }

    /**
     * Determine whether the user can restore the node.
     */
    public function restore(User $user, Node $node): bool
    {
        return $user->can('catat.nodes.delete');
    }

    /**
     * Determine whether the user can permanently delete the node.
     */
    public function forceDelete(User $user, Node $node): bool
    {
        return $user->can('catat.nodes.delete');
    }
}
