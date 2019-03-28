<?php

namespace App\Models;

use App\Traits\Sluggable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use Sluggable,
        SoftDeletes;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['date', 'deleted_at'];

    /**
     * Scope a query to only include archived events.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeArchived($query)
    {
        return $query->whereNotNull('archived_at');
    }

    /**
     * Scope a query to only include scheduled events.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeScheduled($query)
    {
        return $query->where('date', '>', now());
    }

    /**
     * Checks to see if the event is scheduled.
     *
     * @return boolean
     */
    public function isScheduled()
    {
        return $this->date->isFuture();
    }

    /**
     * Checks to see if the event has past.
     *
     * @return boolean
     */
    public function isPast()
    {
        return $this->date->isPast();
    }

    /**
     * Checks to see if the event has been archived.
     *
     * @return boolean
     */
    public function isArchived()
    {
        return $this->archived_at !== null;
    }

    /**
     * Archives a past event.
     *
     * @return $this
     */
    public function archive()
    {
        $this->update(['archived_at' => now()]);

        return $this;
    }
}
