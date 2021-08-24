<?php

namespace App\Models\Contracts;

interface Reinstatable
{
    /**
     * Determine if the model can be reinstated.
     *
     * @return bool
     */
    public function canBeReinstated();
}
