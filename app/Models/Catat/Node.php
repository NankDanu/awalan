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

class Node extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'ct_nodes';

    protected $fillable = [
        'workspace_id',
        'parent_id',
        'type',
        'title',
        'content_md',
        'url',
        'link_type',
        'environment',
        'link_notes',
        'sort_order',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class, 'workspace_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order');
    }

    public function contexts(): HasMany
    {
        return $this->hasMany(NodeContext::class, 'node_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function isFolder(): bool
    {
        return $this->type === 'folder';
    }

    public function isNote(): bool
    {
        return $this->type === 'note';
    }

    public function isLink(): bool
    {
        return $this->type === 'link';
    }
}
