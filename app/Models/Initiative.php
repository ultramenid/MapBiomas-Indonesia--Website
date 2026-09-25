<?php

namespace App\Models;

use App\Models\Concerns\HasBilingualContent;
use Illuminate\Database\Eloquent\Model;

class Initiative extends Model
{
    use HasBilingualContent;

    protected $fillable = [
        'name',
        'slug',
        'logo_path',
        'description_id',
        'description_en',
        'platform_url',
        'accent_color',
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
