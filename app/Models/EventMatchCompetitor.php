<?php

declare(strict_types=1);

namespace App\Models;

use App\Collections\EventMatchCompetitorsCollection;
use Illuminate\Database\Eloquent\Attributes\CollectedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphPivot;

#[CollectedBy(EventMatchCompetitorsCollection::class)]
class EventMatchCompetitor extends MorphPivot
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'events_matches_competitors';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'event_match_id',
        'competitor_id',
        'competitor_type',
        'side_number',
    ];
}
