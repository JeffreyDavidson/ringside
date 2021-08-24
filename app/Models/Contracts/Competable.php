<?php

namespace App\Models\Contracts;

interface Competable
{
    /**
     * Scope a query to include competable models.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder  $query
     */
    public function scopeCompetable($query);

    /**
     * Check to see if the model can be competed for.
     *
     * @return bool
     */
    public function isCompetable();
}
