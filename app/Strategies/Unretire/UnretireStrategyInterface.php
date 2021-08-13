<?php

namespace App\Strategies\Unretire;

use App\Models\Contracts\Unretirable;

interface UnretireStrategyInterface
{
    /**
     * Unretire an unretirable model.
     *
     * @param  string|null $unretiredDate
     * @return void
     */
    public function unretire(string $unretiredDate = null);

    /**
     * Undocumented function.
     *
     * @param  \App\Models\Contracts\Unretirable $unretirable
     */
    public function setUnretirable(Unretirable $unretirable);
}
