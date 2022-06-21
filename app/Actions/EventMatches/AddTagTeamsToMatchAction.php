<?php

declare(strict_types=1);

namespace App\Actions\EventMatches;

use App\Actions\EventMatches\BaseEventMatchAction;
use App\Models\EventMatch;
use App\Models\TagTeam;
use Lorisleiva\Actions\Concerns\AsAction;

class AddTagTeamsToMatchAction extends BaseEventMatchAction
{
    use AsAction;

    /**
     * Add titles to an event match.
     *
     * @param \App\Models\EventMatch $eventMatch
     * @param \Illuminate\Database\Eloquent\Collection<TagTeam> $titles
     * @param int $sideNumber
     * @return void
     */
    public function handle(EventMatch $eventMatch, Collection $tagTeams, int $sideNumber): void
    {
        $tagTeams->each(
            fn (TagTeam $tagTeam) => $this->eventMatchRepository->addTagTeamToMatch(
                $eventMatch,
                $tagTeam,
                $sideNumber
            )
        );
    }
}
