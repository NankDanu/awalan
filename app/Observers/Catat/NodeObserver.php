<?php

declare(strict_types=1);

namespace App\Observers\Catat;

use App\Models\Catat\ActivityLog;
use App\Models\Catat\Node;
use Illuminate\Support\Facades\Auth;

class NodeObserver
{
    /**
     * Handle the Node "created" event.
     */
    public function created(Node $node): void
    {
        $this->writeLog($node, 'created', [
            'after' => $node->getAttributes(),
        ]);
    }

    /**
     * Handle the Node "updated" event.
     */
    public function updated(Node $node): void
    {
        $this->writeLog($node, 'updated', [
            'before' => $node->getOriginal(),
            'after' => $node->getChanges(),
        ]);
    }

    /**
     * Handle the Node "deleted" event.
     */
    public function deleted(Node $node): void
    {
        $this->writeLog($node, 'deleted', [
            'before' => $node->getOriginal(),
        ]);
    }

    protected function writeLog(Node $node, string $action, ?array $changes = null): void
    {
        $userId = Auth::id();
        $actorId = $userId ?? $node->updated_by ?? $node->created_by;

        if ($actorId === null) {
            return;
        }

        ActivityLog::create([
            'user_id' => (string) $actorId,
            'loggable_id' => $node->id,
            'loggable_type' => Node::class,
            'action' => $action,
            'changes' => $changes,
            'description' => sprintf('Node %s: %s', $action, $node->title),
        ]);
    }
}
