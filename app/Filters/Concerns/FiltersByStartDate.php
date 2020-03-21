<?php

namespace App\Filters\Concerns;

trait FiltersByStartDate
{
    /**
     * Filter a query to include models of a specific date started.
     *
     * @param  array  $startedAt
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function startedAt($startedAt)
    {
        if (isset($startedAt[1])) {
            $this->builder->whereHas('currentEmployment', function ($query) use ($startedAt) {
                $query->whereBetween('started_at', [$startedAt[0], $startedAt[1]]);
            });
        } else {
            $this->builder->whereHas('currentEmployment', function ($query) use ($startedAt) {
                $query->whereDate('started_at', $startedAt[0]);
            });
        }

        return $this->builder;
    }
}
