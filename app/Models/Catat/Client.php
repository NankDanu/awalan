<?php

declare(strict_types=1);

namespace App\Models\Catat;

use App\Models\User;
use App\Models\Catat\Workspace;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'ct_clients';

    protected $fillable = [
        'name',
        'company',
        'email',
        'phone',
        'status',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function workspaces(): HasMany
    {
        return $this->hasMany(Workspace::class, 'client_id');
    }

    public function getWorkspacesCountAttribute(): int
    {
        return $this->workspaces()->count();
    }

    public function getWorkspacesOpenCountAttribute(): int
    {
        return $this->workspaces()->where('status', 'open')->count();
    }

    public function getWorkspacesOngoingCountAttribute(): int
    {
        return $this->workspaces()->where('status', 'ongoing')->count();
    }

    public function getWorkspacesClosedCountAttribute(): int
    {
        return $this->workspaces()->where('status', 'closed')->count();
    }
}
