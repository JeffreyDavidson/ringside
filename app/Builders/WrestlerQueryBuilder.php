<?php

namespace App\Builders;

/**
 * @template TModelClass of \App\Models\Wrestler
 * @extends SingleRosterMemberQueryBuilder<TModelClass>
 */
class WrestlerQueryBuilder extends SingleRosterMemberQueryBuilder
{
    /**
     * Scope a query to only include bookable wrestlers.
     *
     * @return \App\Builders\WrestlerQueryBuilder
     */
    public function bookable()
    {
        return $this->whereHas('currentEmployment')
            ->whereDoesntHave('currentSuspension')
            ->whereDoesntHave('currentInjury');
    }
}
