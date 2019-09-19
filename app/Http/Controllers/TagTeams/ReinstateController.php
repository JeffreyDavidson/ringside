<?php

namespace App\Http\Controllers\TagTeams;

use App\Models\TagTeam;
use App\Http\Controllers\Controller;

class ReinstateController extends Controller
{
    /**
     * Reinstate a tag team.
     *
     * @param  \App\Models\TagTeam  $tagTeam
     * @return \lluminate\Http\RedirectResponse
     */
    public function __invoke(TagTeam $tagTeam)
    {
        $this->authorize('reinstate', $tagTeam);

        $tagTeam->reinstate();

        return redirect()->route('tag-teams.index');
    }
}
