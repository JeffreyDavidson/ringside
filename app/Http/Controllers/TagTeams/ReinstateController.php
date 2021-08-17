<?php

namespace App\Http\Controllers\TagTeams;

use App\Exceptions\CannotBeReinstatedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\TagTeams\ReinstateRequest;
use App\Models\TagTeam;
use App\Repositories\TagTeamRepository;

class ReinstateController extends Controller
{
    /**
     * Reinstate a tag team.
     *
     * @param  \App\Models\TagTeam  $tagTeam
     * @param  \App\Http\Requests\TagTeams\ReinstateRequest  $request
     * @param  \App\Repositories\TagTeamRepository $tagTeamRepository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(TagTeam $tagTeam, ReinstateRequest $request, TagTeamRepository $tagTeamRepository)
    {
        throw_unless($tagTeam->canBeReinstated(), new CannotBeReinstatedException);

        $reinstatementDate = now()->toDateTimeString();

        $tagTeamRepository->reinstate($tagTeam, $reinstatementDate);
        // $tagTeam->currentWrestlers->each->reinstate($reinstatementDate);
        $tagTeam->updateStatusAndSave();

        return redirect()->route('tag-teams.index');
    }
}
