<?php

namespace App\Models;

use App\Enums\EventStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes,
        HasFactory,
        Concerns\Unguarded;

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::saving(function ($event) {
            $event->updateStatus();
        });
    }

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'events';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['date'];

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
     * Scope a query to include scheduled events.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeScheduled($query)
    {
        return $query->where('status', EventStatus::SCHEDULED)->whereNotNull('date');
    }

    /**
     * Scope a query to include unscheduled events.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnscheduled($query)
    {
        return $query->where('status', EventStatus::UNSCHEDULED)->whereNull('date');
    }

    /**
     * Scope a query to include past events.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePast($query)
    {
        return $query->where('status', EventStatus::PAST)->where('date', '<', now()->toDateString());
    }

    /**
     * Checks to see if the event is scheduled for a future date.
     *
     * @return bool
     */
    public function isScheduled()
    {
        return $this->date?->isFuture() ?? false;
    }

    /**
     * Checks to see if the event has taken place.
     *
     * @return bool
     */
    public function isPast()
    {
        return $this->date?->isPast() ?? false;
    }

    /**
     * Checks to see if the event has a scheduled date.
     *
     * @return bool
     */
    public function isUnscheduled()
    {
        return $this->date === null;
    }

    /**
     * Retrieve the formatted event date.
     *
     * @return string
     */
    public function getFormattedDateAttribute()
    {
        return $this->date->format('F j, Y');
    }

    /**
     * Update the status for the event.
     *
     * @return $this
     */
    public function updateStatus()
    {
        $this->status = match (true) {
            $this->isScheduled() => EventStatus::SCHEDULED,
            $this->isPast() => EventStatus::PAST,
            default => EventStatus::UNSCHEDULED
        };

        return $this;
    }
}
