<?php

declare(strict_types=1);

namespace App\Http\Livewire\Datatable;

use Illuminate\Database\Query\Builder;

trait WithSorting
{
    /**
     * Undocumented variable.
     *
     * @var array
     */
    private $sorts = [];

    /**
     * Sorts a field by a given key.
     *
     * @param  string $field
     * @return string
     */
    public function sortBy($field)
    {
        if (! isset($this->sorts[$field])) {
            return $this->sorts[$field] = 'asc';
        }

        if ($this->sorts[$field] === 'asc') {
            return $this->sorts[$field] = 'desc';
        }

        unset($this->sorts[$field]);
    }

    /**
     * Undocumented function.
     *
     * @param  \Illuminate\Database\Query\Builder $query
     * @return void
     */
    public function applySorting(Builder $query)
    {
        foreach ($this->sorts as $field => $direction) {
            $query->orderBy($field, $direction);
        }

        return $query;
    }
}
