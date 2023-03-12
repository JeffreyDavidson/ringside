<?php

declare(strict_types=1);

namespace App\Actions\TagTeams;

use App\Events\TagTeams\TagTeamEmployed;
use App\Exceptions\CannotBeEmployedException;
use App\Exceptions\NotEnoughMembersException;
use App\Models\TagTeam;
use Illuminate\Support\Carbon;
use Lorisleiva\Actions\Concerns\AsAction;

class EmployAction extends BaseTagTeamAction
{
    use AsAction;

    /**
     * Employ a tag team.
     */
    public function handle(TagTeam $tagTeam, ?Carbon $startDate = null): void
    {
        $this->ensureCanBeEmployed($tagTeam);

        $startDate ??= now();

        if ($tagTeam->isRetired()) {
            $this->tagTeamRepository->unretire($tagTeam, $startDate);
        }

        $this->tagTeamRepository->employ($tagTeam, $startDate);

        event(new TagTeamEmployed($tagTeam, $startDate));
    }

    /**
     * Ensure a tag team can be employed.
     *
     * @throws \App\Exceptions\CannotBeEmployedException
     */
    private function ensureCanBeEmployed(TagTeam $tagTeam): void
    {
        if ($tagTeam->isCurrentlyEmployed()) {
            throw CannotBeEmployedException::employed($tagTeam);
        }

        if ($tagTeam->currentWrestlers->count() !== TagTeam::NUMBER_OF_WRESTLERS_ON_TEAM) {
            throw new NotEnoughMembersException();
        }
    }
}
