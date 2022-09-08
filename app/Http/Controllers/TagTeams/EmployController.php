<?php

declare(strict_types=1);

namespace App\Http\Controllers\TagTeams;

use App\Actions\TagTeams\EmployAction;
use App\Http\Controllers\Controller;
use App\Models\TagTeam;

class EmployController extends Controller
{
    /**
     * Employ a tag team.
     *
     * @param  \App\Models\TagTeam  $tagTeam
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(TagTeam $tagTeam)
    {
        $this->authorize('employ', $tagTeam);

        EmployAction::run($tagTeam);

        return to_route('tag-teams.index');
    }
}
