<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Staudenmeir\LaravelMergedRelations\Eloquent\HasMergedRelationships;

class EventMatch extends Model
{
    /** @use HasFactory<\Database\Factories\EventMatchFactory> */
    use HasFactory;

    use HasMergedRelationships;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'events_matches';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'event_id',
        'match_type_id',
        'preview',
    ];

    /**
     * Get the referees assigned to the match.
     *
     * @return BelongsToMany<Referee, $this>
     */
    public function referees(): BelongsToMany
    {
        return $this->belongsToMany(Referee::class);
    }

    /**
     * Get the titles being competed for in the match.
     *
     * @return BelongsToMany<Title, $this>
     */
    public function titles(): BelongsToMany
    {
        return $this->belongsToMany(Title::class);
    }

    /**
     * Get the wrestlers involved in the match.
     *
     * @return MorphToMany<Wrestler, $this>
     */
    public function wrestlers(): MorphToMany
    {
        return $this->morphedByMany(Wrestler::class, 'competitor', 'event_match_competitors')
            ->using(EventMatchCompetitor::class)
            ->withPivot('side_number');
    }

    /**
     * Get the tag teams involved in the match.
     *
     * @return MorphToMany<TagTeam, $this>
     */
    public function tagTeams(): MorphToMany
    {
        return $this->morphedByMany(TagTeam::class, 'competitor', 'event_match_competitors')
            ->using(EventMatchCompetitor::class)
            ->withPivot('side_number');
    }
}
