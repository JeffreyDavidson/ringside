<?php

declare(strict_types=1);

namespace App\Builders;

use App\Builders\RefereeQueryBuilder;

/**
 * @template TModelClass of \App\Models\Referee
 *
 * @extends SingleRosterMemberQueryBuilder<\App\Models\Referee>
 */
class RefereeQueryBuilder extends SingleRosterMemberQueryBuilder
{
    /**
     * Scope a query to only include bookable models.
     *
     * @return \App\Builders\RefereeQueryBuilder
     */
    public function bookable(): RefereeQueryBuilder
    {
        return $this->whereHas('currentEmployment')
            ->whereDoesntHave('currentSuspension')
            ->whereDoesntHave('currentInjury');
    }
}
