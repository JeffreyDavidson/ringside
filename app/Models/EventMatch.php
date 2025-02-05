<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class EventMatch extends Model
{
    /** @use HasFactory<\Database\Factories\EventMatchFactory> */
    use HasFactory;

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
     * Get the event the match belongs to.
     *
     * @return BelongsTo<Event, $this>
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the match type of the match.
     *
     * @return BelongsTo<MatchType, $this>
     */
    public function matchType(): BelongsTo
    {
        return $this->belongsTo(MatchType::class);
    }

    /**
     * Get the referees assigned to the match.
     *
     * @return BelongsToMany<Referee, $this>
     */
    public function referees(): BelongsToMany
    {
        return $this->belongsToMany(Referee::class, 'events_matches_referees');
    }

    /**
     * Get the titles being competed for in the match.
     *
     * @return BelongsToMany<Title, $this>
     */
    public function titles(): BelongsToMany
    {
        return $this->belongsToMany(Title::class, 'events_matches_titles');
    }

    /**
     * Get all the event match competitors for the match.
     *
     * @return HasMany<EventMatchCompetitor, $this>
     */
    public function competitors(): HasMany
    {
        return $this->hasMany(EventMatchCompetitor::class);
    }

    /**
     * Get the wrestlers involved in the match.
     *
     * @return MorphToMany<Wrestler, $this>
     */
    public function wrestlers(): MorphToMany
    {
        return $this->morphedByMany(Wrestler::class, 'competitor', 'events_matches_competitors')
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
        return $this->morphedByMany(TagTeam::class, 'competitor', 'events_matches_competitors')
            ->using(EventMatchCompetitor::class)
            ->withPivot('side_number');
    }

    /**
     * Get the tag teams involved in the match.
     *
     * @return HasOne<EventMatchResult, $this>
     */
    public function result(): HasOne
    {
        return $this->hasOne(EventMatchResult::class);
    }
}
