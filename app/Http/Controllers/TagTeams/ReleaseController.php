<?php

namespace App\Http\Controllers\TagTeams;

use App\Actions\TagTeams\ReleaseAction;
use App\Exceptions\CannotBeReleasedException;
use App\Http\Controllers\Controller;
use App\Models\TagTeam;

class ReleaseController extends Controller
{
    /**
     * Release a tag team.
     *
     * @param  \App\Models\TagTeam  $tagTeam
     * @param  \App\Actions\TagTeams\ReleaseAction  $action
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(TagTeam $tagTeam, ReleaseAction $action)
    {
        $this->authorize('release', $tagTeam);

        throw_unless($tagTeam->canBeReleased(), new CannotBeReleasedException);

        $action->handle($tagTeam);

        return redirect()->route('tag-teams.index');
    }
}
