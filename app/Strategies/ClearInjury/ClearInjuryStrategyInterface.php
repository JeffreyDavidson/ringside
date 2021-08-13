<?php

namespace App\Strategies\ClearInjury;

use App\Models\Contracts\Injurable;

interface ClearInjuryStrategyInterface
{
    /**
     * Clear an injury of an injurable model.
     *
     * @param  string|null $recoveryDate
     * @return void
     */
    public function clearInjury(string $recoveryDate = null);

    /**
     * Clear an injury of an injurable model.
     *
     * @param  \App\Models\Contracts\Injurable $injurable
     * @return void
     */
    public function setInjurable(Injurable $injurable);
}
