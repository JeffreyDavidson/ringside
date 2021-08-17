<?php

namespace App\Http\Controllers\TagTeams;

use App\Exceptions\CannotBeSuspendedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\TagTeams\SuspendRequest;
use App\Models\TagTeam;
use App\Repositories\TagTeamRepository;

class SuspendController extends Controller
{
    /**
     * Suspend a tag team.
     *
     * @param  \App\Models\TagTeam  $tagTeam
     * @param  \App\Http\Requests\TagTeams\SuspendRequest  $request
     * @param  \App\Repositories\TagTeamRepository $tagTeamRepository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(TagTeam $tagTeam, SuspendRequest $request, TagTeamRepository $tagTeamRepository)
    {
        throw_unless($tagTeam->canBeSuspended(), new CannotBeSuspendedException);

        $suspensionDate = now()->toDateTimeString();

        $tagTeamRepository->suspend($tagTeam, $suspensionDate);

        $tagTeam->currentWrestlers->each->suspend($suspensionDate);
        $tagTeam->updateStatusAndSave();

        return redirect()->route('tag-teams.index');
    }
}
