<?php

namespace App\Filters;

class StableFilters extends Filters
{
    use Concerns\FiltersByStatus;

    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    public $filters = ['status'];
}
