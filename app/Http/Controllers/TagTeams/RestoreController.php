<?php

declare(strict_types=1);

namespace App\Http\Controllers\TagTeams;

use App\Actions\TagTeams\RestoreAction;
use App\Http\Controllers\Controller;
use App\Models\TagTeam;

class RestoreController extends Controller
{
    /**
     * Restore a deleted tag team.
     *
     * @param  int  $tagTeamId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke($tagTeamId)
    {
        $tagTeam = TagTeam::onlyTrashed()->findOrFail($tagTeamId);

        $this->authorize('restore', $tagTeam);

        RestoreAction::run($tagTeam);

        return to_route('tag-teams.index');
    }
}
