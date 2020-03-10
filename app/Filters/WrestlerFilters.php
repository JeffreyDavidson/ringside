<?php

namespace App\Filters;

class WrestlerFilters extends Filters
{
    use Concerns\FiltersByStartDate,
        Concerns\FiltersByStatus;

    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    public $filters = ['status', 'startedAt'];
}
