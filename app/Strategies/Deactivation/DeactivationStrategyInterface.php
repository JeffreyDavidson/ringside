<?php

namespace App\Strategies\Deactivation;

interface DeactivationStrategyInterface
{
    /**
     * Deactivate a deactivatable model.
     *
     * @param  string|null $deactivationDate
     */
    public function deactivate(string $deactivationDate = null);
}
