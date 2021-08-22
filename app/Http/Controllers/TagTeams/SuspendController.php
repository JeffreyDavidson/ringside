<?php

namespace App\Http\Controllers\TagTeams;

use App\Exceptions\CannotBeSuspendedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\TagTeams\SuspendRequest;
use App\Models\TagTeam;
use App\Repositories\TagTeamRepository;
use App\Repositories\WrestlerRepository;

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
    public function __invoke(
        TagTeam $tagTeam,
        SuspendRequest $request,
        TagTeamRepository $tagTeamRepository,
        WrestlerRepository $wrestlerRepository
    )
    {
        throw_unless($tagTeam->canBeSuspended(), new CannotBeSuspendedException);

        $suspensionDate = now()->toDateTimeString();

        foreach ($tagTeam->currentWrestlers as $wrestler) {
            $wrestlerRepository->suspend($wrestler, $suspensionDate);
            $wrestler->updateStatusAndSave();
        }

        $tagTeamRepository->suspend($tagTeam, $suspensionDate);
        $tagTeam->updateStatusAndSave();

        return redirect()->route('tag-teams.index');
    }
}
