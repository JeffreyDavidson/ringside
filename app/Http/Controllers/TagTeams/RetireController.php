<?php

namespace App\Http\Controllers\TagTeams;

use App\Models\TagTeam;
use App\Http\Controllers\Controller;
use App\Exceptions\CannotBeRetiredException;

class RetireController extends Controller
{
    /**
     * Retire a tag team.
     *
     * @param  \App\Models\TagTeam  $tagTeam
     * @return \lluminate\Http\RedirectResponse
     */
    public function __invoke(TagTeam $tagTeam)
    {
        $this->authorize('retire', $tagTeam);

        if (! $tagTeam->canBeRetired()) {
            throw new CannotBeRetiredException();
        }

        $tagTeam->retire();

        return redirect()->route('tag-teams.index');
    }
}
