<?php

namespace App\Filters;

class TitleFilters extends Filters
{
    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = ['status', 'introduced'];

    /**
     * Filter a query to include titles of a status.
     *
     * @param  string  $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function status($status)
    {
        switch ($status) {
            case 'only_active':
                $this->builder->active();
                break;
            case 'only_inactive':
                $this->builder->inactive();
                break;
            case 'only_retired':
                $this->builder->retired();
                break;
        }

        return $this->builder;
    }

    /**
     * Filter a query to include titles of a specific date introduced.
     *
     * @param  array  $introduced
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function introduced($introduced)
    {
        if (isset($introduced[0]) && !isset($introduced[1])) {
            $this->builder->whereDate('introduced_at', $introduced[0]);
        } elseif (isset($introduced[1])) {
            $this->builder->where('introduced_at', '>=', $introduced[0]);
            $this->builder->where('introduced_at', '<', $introduced[1]);
        }

        return $this->builder;
    }
}
