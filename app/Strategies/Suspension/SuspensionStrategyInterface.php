<?php

namespace App\Strategies\Suspension;

use App\Models\Contracts\Suspendable;

interface SuspensionStrategyInterface
{
    /**
     * Suspend a suspendable model.
     *
     * @param  string|null $suspensionDate
     * @return void
     */
    public function suspend(string $suspensionDate = null);

    /**
     * Undocumented function.
     *
     * @param  \App\Models\Contracts\Suspendable $suspendable
     */
    public function setSuspendable(Suspendable $suspendable);
}
