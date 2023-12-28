<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Models\TitleChampionship;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

trait CanWinTitles
{
    /**
     * Retrieve the titles won by the model.
     *
     * @return MorphTo<TitleChampionship>
     */
    public function titleChampionships(): MorphMany
    {
        return $this->morphMany(TitleChampionship::class, 'champion');
    }
}
