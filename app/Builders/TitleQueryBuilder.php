<?php

namespace App\Builders;

use Illuminate\Database\Eloquent\Builder;

class TitleQueryBuilder extends Builder
{
    /**
     * Scope a query to only include retired models.
     *
     * @return $this
     */
    public function retired()
    {
        return $this->whereHas('currentRetirement');
    }

    /**
     * Scope a query to include current retirement date.
     *
     * @return $this
     */
    public function withCurrentRetiredAtDate()
    {
        return $this->addSelect(['current_retired_at' => Retirement::select('started_at')
            ->whereColumn('retiree_id', $this->getTable().'.id')
            ->where('retiree_type', $this->getMorphClass())
            ->latest('started_at')
            ->limit(1),
        ])->withCasts(['current_retired_at' => 'datetime']);
    }

    /**
     * Scope a query to order by the model's current retirement date.
     *
     * @param  string $direction
     * @return $this
     */
    public function orderByCurrentRetiredAtDate($direction = 'asc')
    {
        return $this->orderByRaw("DATE(current_retired_at) $direction");
    }

    /**
     * Scope a query to only include unactivated models.
     *
     * @return $this
     */
    public function deactivated()
    {
        return $this->whereDoesntHave('currentActivation')
                    ->orWhereDoesntHave('previousActivations');
    }

    /**
     * Scope a query to include current deactivation date.
     *
     * @return $this
     */
    public function withLastDeactivationDate()
    {
        return $this->addSelect(['last_deactivated_at' => Activation::select('ended_at')
            ->whereColumn('activatable_id', $this->qualifyColumn('id'))
            ->where('activatable_type', $this->getMorphClass())
            ->orderBy('ended_at', 'desc')
            ->limit(1),
        ])->withCasts(['last_deactivated_at' => 'datetime']);
    }

    /**
     * Scope a query to order by the models current deactivation date.
     *
     * @param  string $direction
     * @return $this
     */
    public function orderByLastDeactivationDate(string $direction = 'asc')
    {
        return $this->orderByRaw("DATE(last_deactivated_at) $direction");
    }
}
