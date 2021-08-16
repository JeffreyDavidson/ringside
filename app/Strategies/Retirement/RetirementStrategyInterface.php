<?php

namespace App\Strategies\Retirement;

use App\Models\Contracts\Retirable;

interface RetirementStrategyInterface
{
    /**
     * Retire a retirable model.
     *
     * @param  string|null $retirementDate
     * @return void
     */
    public function retire(string $retirementDate = null);

    /**
     * Set the retirable model to be retired.
     *
     * @param  \App\Models\Contracts\Retirable $retirable
     * @return $this
     */
    public function setRetirable(Retirable $retirable);
}
