<?php

namespace App\Filters;

use Illuminate\Support\Str;

class RefereeFilters extends Filters
{
    use Concerns\FiltersByStartDate,
    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = ['status', 'started_at'];

    /**
     * Filter a query to include referees of a status.
     *
     * @param  string  $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function status($status)
    {
        if (method_exists($this->builder->getModel(), 'scope' . Str::studly($status))) {
            $this->builder->{Str::camel($status)}();
        }

        return $this->builder;
    }

}
