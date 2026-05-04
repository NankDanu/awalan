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

class Milestone extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'ct_milestones';

    protected $fillable = [
        'phase_id',
        'name',
        'due_date',
        'is_reached',
        'created_by',
    ];

    protected $casts = [
        'due_date' => 'date',
        'is_reached' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function phase(): BelongsTo
    {
        return $this->belongsTo(Phase::class, 'phase_id');
    }

    public function nodeContexts(): HasMany
    {
        return $this->hasMany(NodeContext::class, 'milestone_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
