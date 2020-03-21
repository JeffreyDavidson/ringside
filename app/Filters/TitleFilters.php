<?php

namespace App\Filters;

class TitleFilters extends Filters
{
    use Concerns\FiltersByStatus;

    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    public $filters = ['status', 'introducedAt'];

    /**
     * Filter a query to include titles of a specific date introduced.
     *
     * @param  array  $introducedAt
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function introducedAt($introducedAt)
    {
        if (isset($introducedAt[1])) {
            $this->builder->whereBetween('introduced_at', [$introducedAt[0], $introducedAt[1]]);
        } elseif (isset($introducedAt[0])) {
            $this->builder->whereDate('introduced_at', $introducedAt[0]);
        }

        return $this->builder;
    }
}
