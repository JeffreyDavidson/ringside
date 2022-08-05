<?php

declare(strict_types=1);

namespace App\Http\Livewire\Datatable;

use Illuminate\Database\Eloquent\Builder;

trait WithSorting
{
    /**
     * Undocumented variable
     *
     * @var array<string, string>
     */
    private $sorts = [];

    /**
     * Undocumented function
     *
     * @param  string  $field
     * @return mixed
     */
    public function sortBy(string $field)
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
     * Undocumented function
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function applySorting(Builder $query)
    {
        foreach ($this->sorts as $field => $direction) {
            $query->orderBy($field, $direction);
        }

        return $query;
    }
}
