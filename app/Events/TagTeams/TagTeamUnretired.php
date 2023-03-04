<?php

namespace App\Events\TagTeams;

use App\Models\TagTeam;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Support\Carbon;

class TagTeamUnretired
{
    use Dispatchable;

    /**
     * Create a new event instance.
     */
    public function __construct(public TagTeam $tagTeam, public Carbon $unretireDate)
    {
    }
}
