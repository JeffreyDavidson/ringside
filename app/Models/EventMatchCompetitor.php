<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use App\Collections\EventMatchCompetitorsCollection;
use Illuminate\Database\Eloquent\Relations\MorphPivot;

class EventMatchCompetitor extends MorphPivot
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'event_match_competitors';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'event_match_id',
        'competitor_id',
        'competitor_type',
        'side_number',
    ];

    /**
     * Retreive the model as the competitor.
     */
    public function competitor(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Create a new Eloquent Collection instance.
     */
    public function newCollection(array $models = []): EventMatchCompetitorsCollection
    {
        return new EventMatchCompetitorsCollection($models);
    }
}
