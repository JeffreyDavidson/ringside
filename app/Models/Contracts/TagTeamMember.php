<?php

namespace App\Models\Contracts;

interface TagTeamMember
{
    /**
     * Get the tag teams the member has been a member of.
     */
    public function tagTeams();

    /**
     * Get the current tag team the member belongs to.
     */
    public function currentTagTeam();

    /**
     * Get the previous tag teams the member has belonged to.
     */
    public function previousTagTeams();
}
