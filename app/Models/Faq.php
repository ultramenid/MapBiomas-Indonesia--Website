<?php

namespace App\Models;

use App\Models\Concerns\HasBilingualContent;
use Illuminate\Database\Eloquent\Model;

/**
 * Maps the legacy `faq` table (questionID, answerID, questionEN, answerEN) —
 * column names are camelCase and must stay unchanged.
 */
class Faq extends Model
{
    use HasBilingualContent;

    protected $table = 'faq';

    protected $fillable = [
        'questionID',
        'questionEN',
        'answerID',
        'answerEN',
    ];
}
