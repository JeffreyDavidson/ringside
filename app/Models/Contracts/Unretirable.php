<?php

namespace App\Models\Contracts;

interface Unretirable
{
    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function canBeUnretired();
}
