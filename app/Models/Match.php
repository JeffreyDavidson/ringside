<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get all of the owning competitor models.
     */
    public function getCompetitorsAttribute()
    {
        return $this->wrestlers->merge($this->tagteams);
    }

    /**
     * Get all of the wrestlers that are assigned this match.
     */
    public function wrestlers()
    {
        return $this->morphedByMany(Wrestler::class, 'competitor', 'match_competitors')->withPivot('side_number');
    }

    /**
     * Get all of the tagteams that are assigned this match.
     */
    public function tagteams()
    {
        return $this->morphedByMany(TagTeam::class, 'competitor', 'match_competitors')->withPivot('side_number');
    }
}
