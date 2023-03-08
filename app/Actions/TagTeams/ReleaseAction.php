<?php

declare(strict_types=1);

namespace App\Actions\TagTeams;

use App\Events\TagTeams\TagTeamReleased;
use App\Exceptions\CannotBeReleasedException;
use App\Models\TagTeam;
use Illuminate\Support\Carbon;
use Lorisleiva\Actions\Concerns\AsAction;

class ReleaseAction extends BaseTagTeamAction
{
    use AsAction;

    /**
     * Release a tag team.
     */
    public function handle(TagTeam $tagTeam, ?Carbon $releaseDate = null): void
    {
        $this->ensureCanBeReleased($tagTeam);

        $releaseDate ??= now();

        if ($tagTeam->isSuspended()) {
            ReinstateAction::run($tagTeam, $releaseDate);
        }

        $this->tagTeamRepository->release($tagTeam, $releaseDate);

        event(new TagTeamReleased($tagTeam, $releaseDate));
    }

    /**
     * Ensure a tag tam can be released.
     *
     * @throws \App\Exceptions\CannotBeReleasedException
     */
    private function ensureCanBeReleased(TagTeam $tagTeam): void
    {
        if ($tagTeam->isUnemployed()) {
            throw CannotBeReleasedException::unemployed($tagTeam);
        }

        if ($tagTeam->hasFutureEmployment()) {
            throw CannotBeReleasedException::hasFutureEmployment($tagTeam);
        }

        if ($tagTeam->isReleased()) {
            throw CannotBeReleasedException::released($tagTeam);
        }

        if ($tagTeam->isRetired()) {
            throw CannotBeReleasedException::retired($tagTeam);
        }
    }
}
