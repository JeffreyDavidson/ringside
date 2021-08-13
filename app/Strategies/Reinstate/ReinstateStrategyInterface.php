<?php

namespace App\Strategies\Reinstate;

use App\Models\Contracts\Reinstatable;

interface ReinstateStrategyInterface
{
    /**
     * Reinstate a reinstatable model.
     *
     * @param  string|null $reinstatementDate
     * @return void
     */
    public function reinstate(string $reinstatementDate = null);

    /**
     * Undocumented function.
     *
     * @param  \App\Models\Contracts\Reinstatable $reinstatable
     */
    public function setReinstatable(Reinstatable $reinstatable);
}
