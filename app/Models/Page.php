<?php

namespace App\Models;

use App\Models\Concerns\HasBilingualContent;
use Illuminate\Database\Eloquent\Model;

/**
 * Maps the legacy `pages` table (name, contentID, contentEN) —
 * column names are camelCase and must stay unchanged.
 */
class Page extends Model
{
    use HasBilingualContent;

    protected $fillable = [
        'name',
        'contentID',
        'contentEN',
    ];
}
