<?php

namespace App\Http\Controllers\TagTeams;

use App\Exceptions\CannotBeUnretiredException;
use App\Http\Controllers\Controller;
use App\Http\Requests\TagTeams\UnretireRequest;
use App\Models\TagTeam;
use App\Repositories\TagTeamRepository;
use App\Repositories\WrestlerRepository;

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
    public function __invoke(
        TagTeam $tagTeam,
        UnretireRequest $request,
        TagTeamRepository $tagTeamRepository,
        WrestlerRepository $wrestlerRepository
    ) {
        throw_unless($tagTeam->canBeUnretired(), new CannotBeUnretiredException);

        $unretiredDate = now()->toDateTimeString();

        $tagTeamRepository->unretire($tagTeam, $unretiredDate);

        foreach ($tagTeam->currentWrestlers as $wrestler) {
            $wrestlerRepository->unretire($wrestler, $unretiredDate);
            $wrestlerRepository->employ($wrestler, $unretiredDate);
            $wrestler->updateStatusAndSave();
        }

        $tagTeamRepository->employ($tagTeam, $unretiredDate);
        $tagTeam->updateStatusAndSave();

        return redirect()->route('tag-teams.index');
    }
}
