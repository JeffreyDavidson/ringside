<?php

namespace App\Http\Controllers\TagTeams;

use App\Models\TagTeam;
use App\Http\Controllers\Controller;

class EmployController extends Controller
{
    /**
     * Employ a pending introduced tag team.
     *
     * @param  App\Models\TagTeam  $tagteam
     * @return \lluminate\Http\RedirectResponse
     */
    public function __invoke(TagTeam $tagteam)
    {
        $this->authorize('employ', $tagteam);

        $tagteam->employ();

        return redirect()->route('tagteams.index');
    }
}
