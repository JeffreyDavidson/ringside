<?php

namespace App\Http\Controllers\TagTeams;

use App\Actions\TagTeams\SuspendAction;
use App\Exceptions\CannotBeSuspendedException;
use App\Http\Controllers\Controller;
use App\Models\TagTeam;

class SuspendController extends Controller
{
    /**
     * Suspend a tag team.
     *
     * @param  \App\Models\TagTeam  $tagTeam
     * @param  \App\Actions\TagTeams\SuspendAction  $action
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(TagTeam $tagTeam, SuspendAction $action)
    {
        $this->authorize('suspend', $tagTeam);

        throw_unless($tagTeam->canBeSuspended(), new CannotBeSuspendedException);

        $action->handle($tagTeam);

        return redirect()->route('tag-teams.index');
    }
}
