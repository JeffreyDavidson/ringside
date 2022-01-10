<?php

namespace App\Http\Controllers\TagTeams;

use App\Actions\TagTeams\EmployAction;
use App\Exceptions\CannotBeEmployedException;
use App\Http\Controllers\Controller;
use App\Models\TagTeam;

class EmployController extends Controller
{
    /**
     * Employ a tag team.
     *
     * @param  \App\Models\TagTeam  $tagTeam
     * @param  \App\Actions\TagTeams\EmployAction  $action
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(TagTeam $tagTeam, EmployAction $action)
    {
        $this->authorize('employ', $tagTeam);

        throw_unless($tagTeam->canBeEmployed(), new CannotBeEmployedException);

        $action->handle($tagTeam);

        return redirect()->route('tag-teams.index');
    }
}
