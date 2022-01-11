<?php

namespace App\Builders;

use App\Enums\EventStatus;
use Illuminate\Database\Eloquent\Builder;

/**
 * The query builder attached to an event.
 *
 * @template TModelClass of \App\Models\Event
 * @extends Builder<TModelClass>
 */
class EventQueryBuilder extends Builder
{
    /**
     * Scope a query to include scheduled events.
     *
     * @return \App\Builders\EventQueryBuilder
     */
    public function scheduled()
    {
        return $this->where('status', EventStatus::scheduled())->whereNotNull('date');
    }

    /**
     * Scope a query to include unscheduled events.
     *
     * @return \App\Builders\EventQueryBuilder
     */
    public function unscheduled()
    {
        return $this->where('status', EventStatus::unscheduled())->whereNull('date');
    }

    /**
     * Scope a query to include past events.
     *
     * @return \App\Builders\EventQueryBuilder
     */
    public function past()
    {
        return $this->where('status', EventStatus::past())->where('date', '<', now()->toDateString());
    }
}
