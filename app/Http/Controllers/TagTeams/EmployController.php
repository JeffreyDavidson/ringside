<?php

namespace App\Http\Controllers\TagTeams;

use App\Models\TagTeam;
use App\Http\Controllers\Controller;

class EmployController extends Controller
{
    /**
     * Employ a pending introduced tag team.
     *
     * @param  App\Models\TagTeam  $tagTeam
     * @return \lluminate\Http\RedirectResponse
     */
    public function __invoke(TagTeam $tagTeam)
    {
        $this->authorize('employ', $tagTeam);

        $tagTeam->employ();

        return redirect()->route('tag-teams.index');
    }
}
