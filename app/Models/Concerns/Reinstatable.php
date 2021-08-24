<?php

namespace App\Models\Concerns;

trait Reinstatable
{
    /**
     * Determine if the model can be reinstated.
     *
     * @return bool
     */
    public function canBeReinstated()
    {
        if (! $this->isSuspended()) {
            return false;
        }

        return true;
    }
}
