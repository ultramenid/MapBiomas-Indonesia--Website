<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    public const CATEGORY_COCREATOR = 'cocreator';
    public const CATEGORY_SUPPORTED = 'supported';

    protected $fillable = [
        'name',
        'logo_path',
        'url',
        'category',
        'sort',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort');
    }
}
