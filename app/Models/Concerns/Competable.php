<?php

namespace App\Models\Concerns;

use App\Enums\TitleStatus;

trait Competable
{
    /**
     * Scope a query to only include competable models.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCompetable($query)
    {
        return $query->where('status', TitleStatus::ACTIVE);
    }

    /**
     * Check to see if the model can be competed for.
     *
     * @return bool
     */
    public function isCompetable()
    {
        if ($this->isNotActivated() || $this->isDeactivated() || $this->isRetired() || $this->hasFutureActivation()) {
            return false;
        }

        return true;
    }
}
