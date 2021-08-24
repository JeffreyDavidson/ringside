<?php

namespace App\Models\Contracts;

interface Competable
{
    /**
     * Scope a query to only include competable models.
     *
     * @return mixed
     */
    public function scopeCompetable();

    /**
     * Check to see if the model can be competed for.
     *
     * @return string
     */
    public function isCompetable();
}
