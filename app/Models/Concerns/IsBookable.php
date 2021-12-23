<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait IsBookable
{
    /**
     * Scope a query to only include bookable models.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIsBookable(Builder $query)
    {
        return $query->whereHas('currentEmployment')
                    ->whereDoesntHave('currentSuspension')
                    ->whereDoesntHave('currentInjury');
    }
}
