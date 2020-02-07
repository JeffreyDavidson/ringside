<?php

namespace App\Http\Controllers\TagTeams;

use App\Models\TagTeam;
use App\Http\Controllers\Controller;
use App\Exceptions\CannotBeUnretiredException;

class UnretireController extends Controller
{
    /**
     * Unretire a tag team.
     *
     * @param  \App\Models\TagTeam  $tagTeam
     * @return \lluminate\Http\RedirectResponse
     */
    public function __invoke(TagTeam $tagTeam)
    {
        $this->authorize('unretire', $tagTeam);

        if (! $tagTeam->canBeUnretired()) {
            throw new CannotBeUnretiredException();
        }

        $tagTeam->unretire();

        return redirect()->route('tag-teams.index');
    }
}
