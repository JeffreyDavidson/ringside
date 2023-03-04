<?php

declare(strict_types=1);

namespace App\Actions\TagTeams;

use App\Actions\Wrestlers\RetireAction as WrestlersRetireAction;
use App\Events\TagTeams\TagTeamRetired;
use App\Exceptions\CannotBeRetiredException;
use App\Models\TagTeam;
use Illuminate\Support\Carbon;
use Lorisleiva\Actions\Concerns\AsAction;

class RetireAction extends BaseTagTeamAction
{
    use AsAction;

    /**
     * Retire a tag team.
     */
    public function handle(TagTeam $tagTeam, ?Carbon $retirementDate = null): void
    {
        $this->ensureCanBeRetired($tagTeam);

        $retirementDate ??= now();

        if ($tagTeam->isSuspended()) {
            ReinstateAction::run($tagTeam, $retirementDate);
        }

        $this->tagTeamRepository->release($tagTeam, $retirementDate);
        $this->tagTeamRepository->retire($tagTeam, $retirementDate);

        event(new TagTeamRetired($tagTeam, $retirementDate));
    }

    /**
     * Ensure a tag team can be retired.
     *
     * @throws \App\Exceptions\CannotBeRetiredException
     */
    private function ensureCanBeRetired(TagTeam $tagTeam): void
    {
        if ($tagTeam->isUnemployed()) {
            throw CannotBeRetiredException::unemployed($tagTeam);
        }

        if ($tagTeam->hasFutureEmployment()) {
            throw CannotBeRetiredException::hasFutureEmployment($tagTeam);
        }

        if ($tagTeam->isRetired()) {
            throw CannotBeRetiredException::retired($tagTeam);
        }

        if ($tagTeam->isReleased()) {
            throw CannotBeRetiredException::released($tagTeam);
        }
    }
}
