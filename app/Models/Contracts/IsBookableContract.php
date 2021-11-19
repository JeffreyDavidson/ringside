<?php

namespace App\Models\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface IsBookableContract
{
    /**
     * Check to see if the model is bookable.
     *
     * @return bool
     */
    public function isBookable();

    /**
     * Scope a query to include bookable models.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder  $query
     */
    public function scopeIsBookable(Builder $query);
}
