<?php

namespace App\Models\Concerns;

/**
 * Bilingual column-pair helper: content is stored in paired ID/EN columns
 * — either camelCase on legacy tables (`contentID`/`contentEN`) or
 * snake_case on new tables (`title_id`/`title_en`) — and resolved by
 * app locale with a fallback to the other language when empty.
 */
trait HasBilingualContent
{
    public function bi(string $base, ?string $locale = null): ?string
    {
        $locale ??= app()->getLocale();

        $primary = $this->biColumn($base, $locale === 'id' ? 'ID' : 'EN');
        $fallback = $this->biColumn($base, $locale === 'id' ? 'EN' : 'ID');

        if ($primary === null || trim(strip_tags((string) $primary)) === '') {
            return $fallback;
        }

        return $primary;
    }

    protected function biColumn(string $base, string $suffix): ?string
    {
        foreach ([$base . $suffix, $base . '_' . strtolower($suffix)] as $column) {
            if (array_key_exists($column, $this->attributes)) {
                return $this->attributes[$column];
            }
        }

        return null;
    }
}
