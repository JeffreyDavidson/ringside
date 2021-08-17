<?php

namespace App\Http\Controllers\TagTeams;

use App\Exceptions\CannotBeUnretiredException;
use App\Http\Controllers\Controller;
use App\Http\Requests\TagTeams\UnretireRequest;
use App\Models\TagTeam;
use App\Repositories\TagTeamRepository;

class UnretireController extends Controller
{
    /**
     * Unretire a tag team.
     *
     * @param  \App\Models\TagTeam  $tagTeam
     * @param  \App\Http\Requests\TagTeams\UnretireRequest  $request
     * @param  \App\Repositories\TagTeamRepository  $tagTeamRepository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(TagTeam $tagTeam, UnretireRequest $request, TagTeamRepository $tagTeamRepository)
    {
        throw_unless($tagTeam->canBeUnretired(), new CannotBeUnretiredException);

        $unretiredDate = now()->toDateTimeString();

        $tagTeamRepository->unretire($tagTeam, $unretiredDate);
        $tagTeam->updateStatusAndSave();
        $tagTeam->currentWrestlers->each->unretire($unretiredDate);
        $tagTeam->currentWrestlers->each->upddateStatusAndSave();

        $tagTeamRepository->employ($tagTeam, $unretiredDate);
        $tagTeam->updateStatusAndSave();
        $tagTeam->currentWrestlers->each->employ($unretiredDate);
        $tagTeam->currentWrestlers->each->upddateStatusAndSave();

        return redirect()->route('tag-teams.index');
    }
}
