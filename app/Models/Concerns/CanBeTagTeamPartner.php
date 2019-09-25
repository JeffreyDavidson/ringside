<?php

namespace App\Models\Concerns;

trait CanBeTagTeamPartner
{
    /**
     * Get the tag team history the wrestler has belonged to.
     *
     * @return App\Eloquent\Relationships\LeaveableBelongsToMany
     */
    public function tagTeamHistory()
    {
        return $this->leaveableBelongsToMany(TagTeam::class, 'tag_team_wrestler', 'wrestler_id', 'tag_team_id');
    }

    /**
     * Get the current tag team of the wrestler.
     *
     * @return App\Eloquent\Relationships\LeaveableBelongsToMany
     */
    public function currentTagTeam()
    {
        return $this->tagTeamHistory()->where('status', 'bookable')->current();
    }

    /**
     * Get the previous tag teams the wrestler has belonged to.
     *
     * @return App\Eloquent\Relationships\LeaveableBelongsToMany
     */
    public function previousTagTeams()
    {
        return $this->tagTeamHistory()->detached();
    }
}
