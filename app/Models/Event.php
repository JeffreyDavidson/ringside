<?php

declare(strict_types=1);

namespace App\Models;

use App\Builders\EventQueryBuilder;
use App\Enums\EventStatus;
use App\Presenters\EventPresenter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'date', 'venue_id', 'preview', 'status'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'datetime',
        'status' => EventStatus::class,
    ];

    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return \App\Builders\EventQueryBuilder<Event>
     */
    public function newEloquentBuilder($query): EventQueryBuilder
    {
        return new EventQueryBuilder($query);
    }

    /**
     * Present the event model.
     *
     * @return \App\Presenters\EventPresenter
     */
    public function present()
    {
        return new EventPresenter($this);
    }

    /**
     * Retrieve the venue of the event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    /**
     * Retrieve the matches for the event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function matches()
    {
        return $this->hasMany(EventMatch::class);
    }

    /**
     * Checks to see if the event is scheduled for a future date.
     *
     * @return bool
     */
    public function isScheduled()
    {
        if (is_null($this->date)) {
            return false;
        }

        return $this->date->isFuture();
    }

    /**
     * Checks to see if the event has already taken place.
     *
     * @return bool
     */
    public function isPast()
    {
        if (is_null($this->date)) {
            return false;
        }

        return $this->date->isPast();
    }

    /**
     * Checks to see if the event is unscheduled.
     *
     * @return bool
     */
    public function isUnscheduled()
    {
        return $this->date === null;
    }
}
