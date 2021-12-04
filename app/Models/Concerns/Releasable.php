<?php

namespace App\Models\Concerns;

trait Releasable
{
    /**
     * Check to see if the model has been released.
     *
     * @return bool
     */
    public function isReleased()
    {
        return $this->previousEmployment()->exists() &&
                $this->futureEmployment()->doesntExist() &&
                $this->currentEmployment()->doesntExist() &&
                $this->currentRetirement()->doesntExist();
    }

    /**
     * Determine if the model can be released.
     *
     * @return bool
     */
    public function canBeReleased()
    {
        if ($this->isNotInEmployment()) {
            return false;
        }

        return true;
    }
}
