<?php

namespace App\Builders;

/**
 * The query builder attached to a wrestler.
 *
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
