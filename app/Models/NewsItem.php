<?php

namespace App\Models;

use App\Models\Concerns\HasBilingualContent;
use Illuminate\Database\Eloquent\Model;

class NewsItem extends Model
{
    use HasBilingualContent;

    public const TYPE_INTERNAL = 'internal';
    public const TYPE_EXTERNAL = 'external';

    protected $table = 'news';

    protected $fillable = [
        'type',
        'title_id',
        'title_en',
        'slug',
        'excerpt_id',
        'excerpt_en',
        'image_path',
        'external_url',
        'content_id',
        'content_en',
        'published_at',
        'is_published',
        'sort',
    ];

    protected $casts = [
        'published_at' => 'date',
        'is_published' => 'boolean',
    ];

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeInternal($query)
    {
        return $query->where('type', self::TYPE_INTERNAL);
    }

    public function isExternal(): bool
    {
        return $this->type === self::TYPE_EXTERNAL;
    }
}
