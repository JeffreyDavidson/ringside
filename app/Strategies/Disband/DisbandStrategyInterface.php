<?php

namespace App\Strategies\Disband;

use App\Models\Contracts\Disbandable;

interface DisbandStrategyInterface
{
    /**
     * Disband a disbandable model.
     *
     * @param  string|null $disbandDate
     * @return void
     */
    public function disband(string $disbandDate = null);

    /**
     * Set the disbandable model to be disbanded.
     *
     * @param  \App\Models\Contracts\Disbandable $disbandable
     */
    public function setDisbandable(Disbandable $disbandable);
}
