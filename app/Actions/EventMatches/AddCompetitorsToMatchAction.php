<?php

declare(strict_types=1);

namespace App\Actions\EventMatches;

use App\Actions\EventMatches\AddTagTeamsToMatchAction;
use App\Actions\EventMatches\AddWrestlersToMatchAction;
use App\Actions\EventMatches\BaseEventMatchAction;
use App\Models\EventMatch;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class AddCompetitorsToMatchAction extends BaseEventMatchAction
{
    use AsAction;

    /**
     * Add competitors to an event match.
     *
     * @param \App\Models\EventMatch $eventMatch
     * @param \Illuminate\Database\Eloquent\Collection $competitors
     * @return void
     */
    public function handle(EventMatch $eventMatch, $competitors): void
    {
        $competitors->each(function ($sideCompetitors, $sideNumber) use ($eventMatch) {
            if (Arr::exists($sideCompetitors, 'wrestlers')) {
                AddWrestlersToMatchAction::run($eventMatch, Arr::get($sideCompetitors, 'wrestlers'), $sideNumber);
            }

            if (Arr::exists($sideCompetitors, 'tag_teams')) {
                AddTagTeamsToMatchAction::run($eventMatch, Arr::get($sideCompetitors, 'tag_teams'), $sideNumber);
            }
        });
    }
}
