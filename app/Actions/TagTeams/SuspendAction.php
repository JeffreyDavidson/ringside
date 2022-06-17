<?php

declare(strict_types=1);

namespace App\Actions\TagTeams;

use App\Actions\Wrestlers\WrestlerSuspendAction;
use App\Models\TagTeam;
use Lorisleiva\Actions\Concerns\AsAction;

class SuspendAction extends BaseTagTeamAction
{
    use AsAction;

    /**
     * Suspend a tag team.
     *
     * @param  \App\Models\TagTeam  $tagTeam
     * @return void
     */
    public function handle(TagTeam $tagTeam): void
    {
        $suspensionDate = now();

        $tagTeam->currentWrestlers->each(fn ($wrestler) =>  WrestlerSuspendAction::run($wrestler, $suspensionDate));

        $this->tagTeamRepository->suspend($tagTeam, $suspensionDate);
    }
}
