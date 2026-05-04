<?php

declare(strict_types=1);

namespace App\Models\Catat;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Workspace extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'ct_workspaces';

    protected $fillable = [
        'client_id',
        'name',
        'slug',
        'is_project',
        'status',
        'type_tag',
        'is_archived',
        'archived_at',
        'description',
        'created_by',
    ];

    protected $casts = [
        'is_project' => 'boolean',
        'is_archived' => 'boolean',
        'archived_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function nodes(): HasMany
    {
        return $this->hasMany(Node::class, 'workspace_id');
    }

    public function phases(): HasMany
    {
        return $this->hasMany(Phase::class, 'workspace_id')->orderBy('sort_order');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'ct_workspace_tag', 'workspace_id', 'tag_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_archived', false);
    }

    public function scopeProject(Builder $query): Builder
    {
        return $query->where('is_project', true);
    }

    public function scopeNotProject(Builder $query): Builder
    {
        return $query->where('is_project', false);
    }
}
