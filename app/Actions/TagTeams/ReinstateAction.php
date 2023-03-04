<?php

declare(strict_types=1);

namespace App\Actions\TagTeams;

use App\Events\TagTeams\TagTeamReinstated;
use App\Exceptions\CannotBeReinstatedException;
use App\Models\TagTeam;
use Illuminate\Support\Carbon;
use Lorisleiva\Actions\Concerns\AsAction;

class ReinstateAction extends BaseTagTeamAction
{
    use AsAction;

    /**
     * Reinstate a tag team.
     */
    public function handle(TagTeam $tagTeam, ?Carbon $reinstatementDate = null): void
    {
        $this->ensureCanBeReinstated($tagTeam);

        $reinstatementDate ??= now();

        $this->tagTeamRepository->reinstate($tagTeam, $reinstatementDate);

        event(new TagTeamReinstated($tagTeam, $reinstatementDate));
    }

    /**
     * Ensure a tag team can be reinstated.
     *
     * @throws \App\Exceptions\CannotBeReinstatedException
     */
    private function ensureCanBeReinstated(TagTeam $tagTeam): void
    {
        if (! $tagTeam->canBeReinstated()) {
            throw CannotBeReinstatedException::notSuspended($tagTeam);
        }
    }
}
