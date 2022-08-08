<?php

declare(strict_types=1);

namespace App\Models\Concerns;

trait CanJoinTagTeams
{
    /**
     * Get the previous tag teams the member has belonged to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function previousTagTeams()
    {
        return $this->tagTeams()
            ->withPivot(['joined_at', 'left_at'])
            ->wherePivotNotNull('left_at');
    }

    /**
     * Determine if wrestler can is a member of a current tag team.
     *
     * @return bool
     */
    public function isAMemberOfCurrentTagTeam()
    {
        return $this->currentTagTeam !== null && $this->currentTagTeam->exists();
    }
}
