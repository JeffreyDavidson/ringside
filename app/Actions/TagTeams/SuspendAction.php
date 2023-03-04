<?php

declare(strict_types=1);

namespace App\Actions\TagTeams;

use App\Events\TagTeams\TagTeamSuspended;
use App\Exceptions\CannotBeSuspendedException;
use App\Models\TagTeam;
use Illuminate\Support\Carbon;
use Lorisleiva\Actions\Concerns\AsAction;

class SuspendAction extends BaseTagTeamAction
{
    use AsAction;

    /**
     * Suspend a tag team.
     */
    public function handle(TagTeam $tagTeam, ?Carbon $suspensionDate = null): void
    {
        $this->ensureCanBeSuspended($tagTeam);

        $suspensionDate ??= now();

        $this->tagTeamRepository->suspend($tagTeam, $suspensionDate);

        event(new TagTeamSuspended($tagTeam, $suspensionDate));
    }

    /**
     * Ensure a tag team can be suspended.
     *
     * @throws \App\Exceptions\CannotBeSuspendedException
     */
    private function ensureCanBeSuspended(TagTeam $tagTeam): void
    {
        if ($tagTeam->isUnemployed()) {
            throw CannotBeSuspendedException::unemployed($tagTeam);
        }

        if ($tagTeam->isReleased()) {
            throw CannotBeSuspendedException::released($tagTeam);
        }

        if ($tagTeam->isRetired()) {
            throw CannotBeSuspendedException::retired($tagTeam);
        }

        if ($tagTeam->hasFutureEmployment()) {
            throw CannotBeSuspendedException::hasFutureEmployment($tagTeam);
        }

        if ($tagTeam->isSuspended()) {
            throw CannotBeSuspendedException::suspended($tagTeam);
        }
    }
}
