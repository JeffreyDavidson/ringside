<?php

namespace App\Strategies\Activation;

use App\Models\Contracts\Activatable;

interface ActivationStrategyInterface
{
    /**
     * Activate an activatable model.
     *
     * @param  string|null $activationDate
     * @return void
     */
    public function activate(string $activationDate = null);

    /**
     * Undocumented function.
     *
     * @param  \App\Models\Contracts\Activatable $activatable
     */
    public function setActivatable(Activatable $activatable);
}
