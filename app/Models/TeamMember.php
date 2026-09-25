<?php

namespace App\Models;

use App\Models\Concerns\HasBilingualContent;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasBilingualContent;

    public const CATEGORY_TECHNICAL = 'technical';
    public const CATEGORY_SCIENTIFIC = 'scientific';

    public const GROUP_KOORDINATOR = 'koordinator';
    public const GROUP_INTI = 'inti';
    public const GROUP_REGIO = 'regio';

    /** Baseline collection numbers shipped with the code; higher numbers may be added live in the CMS. */
    public const COLLECTION_MAX = ['landy' => 4, 'fire' => 1, 'alerta' => 1];

    protected $fillable = [
        'category',
        'team_group',
        'region',
        'name',
        'position_id',
        'position_en',
        'photo_path',
        'email',
        'bio_id',
        'bio_en',
        'collections',
        'sort',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'collections' => 'array',
    ];

    /** Baseline counts merged with the highest collection number ever saved, per initiative. */
    public static function collectionMaxInUse(): array
    {
        $max = self::COLLECTION_MAX;

        self::query()->get(['collections'])->each(function (self $member) use (&$max) {
            foreach ($max as $initiative => $highest) {
                $stored = array_map('intval', $member->collections[$initiative] ?? []);
                $max[$initiative] = max($highest, ...($stored ?: [0]));
            }
        });

        return $max;
    }

    public function scopeTechnical($query)
    {
        return $query->where('category', self::CATEGORY_TECHNICAL);
    }

    public function scopeScientific($query)
    {
        return $query->where('category', self::CATEGORY_SCIENTIFIC);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
