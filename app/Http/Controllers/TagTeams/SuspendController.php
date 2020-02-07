<?php

namespace App\Http\Controllers\TagTeams;

use App\Models\TagTeam;
use App\Http\Controllers\Controller;
use App\Exceptions\CannotBeSuspendedException;

class SuspendController extends Controller
{
    /**
     * Suspend a tag team.
     *
     * @param  \App\Models\TagTeam  $tagTeam
     * @return \lluminate\Http\RedirectResponse
     */
    public function __invoke(TagTeam $tagTeam)
    {
        $this->authorize('suspend', $tagTeam);

        if (! $tagTeam->canBeSuspended()) {
            throw new CannotBeSuspendedException();
        }

        $tagTeam->suspend();

        return redirect()->route('tag-teams.index');
    }
}
