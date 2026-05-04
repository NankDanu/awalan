<?php

declare(strict_types=1);

namespace App\Models\Catat;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NodeContext extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'ct_node_contexts';

    protected $fillable = [
        'node_id',
        'phase_id',
        'milestone_id',
        'context_type',
        'note',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function node(): BelongsTo
    {
        return $this->belongsTo(Node::class, 'node_id');
    }

    public function phase(): BelongsTo
    {
        return $this->belongsTo(Phase::class, 'phase_id');
    }

    public function milestone(): BelongsTo
    {
        return $this->belongsTo(Milestone::class, 'milestone_id');
    }
}
