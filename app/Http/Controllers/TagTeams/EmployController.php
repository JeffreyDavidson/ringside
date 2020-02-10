<?php

namespace App\Http\Controllers\TagTeams;

use App\Exceptions\CannotBeEmployedException;
use App\Http\Controllers\Controller;
use App\Models\TagTeam;

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

        if (! $tagTeam->canBeEmployed()) {
            throw new CannotBeEmployedException();
        }

        $tagTeam->employ();

        return redirect()->route('tag-teams.index');
    }
}
