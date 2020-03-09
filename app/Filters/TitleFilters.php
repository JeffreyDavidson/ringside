<?php

namespace App\Filters;

use Carbon\Carbon;

class TitleFilters extends Filters
{
    use Concerns\FiltersByStatus;

    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    public $filters = ['status', 'introduced_at'];

    /**
     * Filter a query to include titles of a specific date introduced.
     *
     * @param  array  $introduced
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function introducedAt($introducedAt)
    {
        if (isset($introducedAt[1])) {
            $this->builder->whereBetween('started_at', [
                Carbon::parse($introducedAt[0]),
                Carbon::parse($introducedAt[1])
            ]);
        } elseif (isset($introducedAt[0])) {
            $this->builder->whereDate('introduced_at', Carbon::parse($introducedAt[0]));
        }

        return $this->builder;
    }
}
