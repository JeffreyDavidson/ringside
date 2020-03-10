<?php

namespace App\Filters;

class EventFilters extends Filters
{
    use Concerns\FiltersByStatus;

    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    public $filters = ['status', 'date'];

    /**
     * Filter a query to include events of a specific date.
     *
     * @param  array  $date
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function date($date)
    {
        if (isset($date[1])) {
            $this->builder->whereBetween('date', [
                $date[0],
                $date[1],
            ]);
        } elseif (isset($date[0])) {
            $this->builder->whereDate('date', $date[0]);
        }

        return $this->builder;
    }
}
