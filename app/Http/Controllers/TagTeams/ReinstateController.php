<?php

namespace App\Http\Controllers\TagTeams;

use App\Actions\TagTeams\ReinstateAction;
use App\Exceptions\CannotBeReinstatedException;
use App\Http\Controllers\Controller;
use App\Models\TagTeam;

class ReinstateController extends Controller
{
    /**
     * Reinstate a tag team.
     *
     * @param  \App\Models\TagTeam  $tagTeam
     * @param  \App\Actions\TagTeams\ReinstateAction  $action
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(TagTeam $tagTeam, ReinstateAction $action)
    {
        $this->authorize('reinstate', $tagTeam);

        throw_unless($tagTeam->canBeReinstated(), new CannotBeReinstatedException);

        $action->handle($tagTeam);

        return redirect()->route('tag-teams.index');
    }
}
