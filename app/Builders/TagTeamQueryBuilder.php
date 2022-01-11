<?php

namespace App\Builders;

/**
 * The query builder attached to a tag team.
 *
 * @template TModelClass of \App\Models\TagTeam
 * @extends RosterMemberQueryBuilder<TModelClass>
 */
class TagTeamQueryBuilder extends RosterMemberQueryBuilder
{
    /**
     * Scope a query to only include bookable tag teams.
     *
     * @return \App\Builders\TagTeamQueryBuilder
     */
    public function bookable()
    {
        return $this->where('status', 'bookable');
    }
}
