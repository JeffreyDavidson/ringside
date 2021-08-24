<?php

namespace App\Models\Contracts;

interface Bookable
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
    public function scopeBookable($query);
}
