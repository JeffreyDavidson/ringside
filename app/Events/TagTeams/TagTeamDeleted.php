<?php

namespace App\Events\TagTeams;

use App\Models\TagTeam;
use Illuminate\Foundation\Events\Dispatchable;

class TagTeamDeleted
{
    use Dispatchable;

    /**
     * Create a new event instance.
     */
    public function __construct(public TagTeam $tagTeam)
    {
    }
}
