<?php

declare(strict_types=1);

namespace Nank\Awalan\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanySetting extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cf_company_settings';

    protected $fillable = [
        'company_name',
        'address',
        'phone',
        'email',
        'website',
        'description',
        'logo',
        'favicon',
        'login_background',
        'primary_color',
        'secondary_color',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }
}
