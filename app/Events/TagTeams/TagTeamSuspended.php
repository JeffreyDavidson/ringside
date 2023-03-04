<?php

namespace App\Events\TagTeams;

use App\Models\TagTeam;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Support\Carbon;

class TagTeamSuspended
{
    use Dispatchable;

    /**
     * Create a new event instance.
     */
    public function __construct(public TagTeam $tagTeam, public Carbon $suspensionDate)
    {
    }
}
