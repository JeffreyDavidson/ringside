<?php

namespace App\Strategies\Release;

use App\Models\Contracts\Releasable;

interface ReleaseStrategyInterface
{
    /**
     * Release a releasable model.
     *
     * @param  string|null $releaseDate
     * @return void
     */
    public function release(string $releaseDate = null);

    /**
     * Undocumented function.
     *
     * @param  \App\Models\Contracts\Releasable $releasable
     */
    public function setReleasable(Releasable $releasable);
}
