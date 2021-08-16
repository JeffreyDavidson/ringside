<?php

namespace App\Strategies\Injury;

use App\Models\Contracts\Injurable;

interface InjuryStrategyInterface
{
    /**
     * Injure an injurable model.
     *
     * @param  string|null $injureDate
     */
    public function injure(string $injureDate = null);

    /**
     * Set the injurable model to be injured.
     *
     * @param  \App\Models\Contracts\Injurable $injurable
     */
    public function setInjurable(Injurable $injurable);
}
