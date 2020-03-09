<?php

namespace App\Filters;

class TagTeamFilters extends Filters
{
    use Concerns\FiltersByStartDate,
        Concerns\FiltersByStatus;

    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    public $filters = ['status', 'started_at'];
}
