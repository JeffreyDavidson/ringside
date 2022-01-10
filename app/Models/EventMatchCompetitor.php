<?php

namespace App\Models;

use App\Collections\EventMatchCompetitorsCollection;
use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Relations\MorphPivot;

class EventMatchCompetitor extends MorphPivot
{
    protected $table = 'event_match_competitors';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['event_match_id', 'event_match_competitor_id', 'event_match_competitor_type', 'side_number'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function competitor()
    {
        return $this->morphTo();
    }

    /**
     * Create a new Eloquent Collection instance.
     *
     * @param  array  $models
     *
     * @return \App\Collections\EventMatchCompetitorsCollection
     */
    public function newCollection(array $models = [])
    {
        return new EventMatchCompetitorsCollection($models);
    }
}
