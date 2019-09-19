<?php

namespace App\Http\Controllers\TagTeams;

use App\Models\TagTeam;
use App\Http\Controllers\Controller;

class RestoreController extends Controller
{
    /**
     * Restore a deleted tag team.
     *
     * @param  int  $tagTeamId
     * @return \lluminate\Http\RedirectResponse
     */
    public function __invoke($tagTeamId)
    {
        $tagTeam = TagTeam::onlyTrashed()->findOrFail($tagTeamId);

        $this->authorize('restore', $tagTeam);

        $tagTeam->restore();

        return redirect()->route('tag-teams.index');
    }
}
