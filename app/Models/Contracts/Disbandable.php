<?php

namespace App\Models\Contracts;

interface Disbandable
{
    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function canBeDisbanded();
}
