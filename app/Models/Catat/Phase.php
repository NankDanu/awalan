<?php

declare(strict_types=1);

namespace App\Models\Catat;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Phase extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'ct_phases';

    protected $fillable = [
        'workspace_id',
        'name',
        'start_date',
        'end_date',
        'status',
        'sort_order',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'sort_order' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class, 'workspace_id');
    }

    public function milestones(): HasMany
    {
        return $this->hasMany(Milestone::class, 'phase_id')->orderBy('due_date');
    }

    public function nodeContexts(): HasMany
    {
        return $this->hasMany(NodeContext::class, 'phase_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
