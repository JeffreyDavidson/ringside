<?php

namespace App\Http\Controllers\TagTeams;

use App\Exceptions\CannotBeReleasedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\TagTeams\ReleaseRequest;
use App\Models\TagTeam;
use App\Repositories\TagTeamRepository;

class ReleaseController extends Controller
{
    /**
     * Release a tag team.
     *
     * @param  \App\Models\TagTeam  $tagTeam
     * @param  \App\Http\Requests\TagTeams\ReleaseRequest  $request
     * @param  \App\Repositories\TagTeamRepository $tagTeamRepository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(TagTeam $tagTeam, ReleaseRequest $request, TagTeamRepository $tagTeamRepository)
    {
        throw_unless($tagTeam->canBeReleased(), new CannotBeReleasedException);

        $releaseDate = now()->toDateTimeString();

        $tagTeamRepository->release($tagTeam, $releaseDate);
        $tagTeam->updateStatusAndSave();

        return redirect()->route('tag-teams.index');
    }
}
