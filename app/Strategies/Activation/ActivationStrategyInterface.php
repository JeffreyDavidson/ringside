<?php

namespace App\Strategies\Activation;

interface ActivationStrategyInterface
{
    /**
     * Activate an activatable model.
     *
     * @param  string|null $activationDate
     * @return void
     */
    public function activate(string $activationDate = null);
}
