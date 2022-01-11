<?php

namespace App\Actions\Stables;

use App\Models\Stable;
use App\Models\TagTeam;
use App\Models\Wrestler;
use Lorisleiva\Actions\Concerns\AsAction;

class ActivateAction extends BaseStableAction
{
    use AsAction;

    /**
     * Activate a stable.
     *
     * @param  \App\Models\Stable  $stable
     *
     * @return void
     */
    public function handle(Stable $stable): void
    {
        $activationDate = now();

        if ($stable->currentWrestlers->isNotEmpty()) {
            $stable->currentWrestlers->each(function (Wrestler $wrestler) use ($activationDate) {
                $this->wrestlerRepository->employ($wrestler, $activationDate);
                $wrestler->save();
            });
        }

        if ($stable->currentTagTeams->isNotEmpty()) {
            $stable->currentTagTeams->each(function (TagTeam $tagTeam) use ($activationDate) {
                $tagTeam->currentWrestlers->each(function (Wrestler $wrestler) use ($activationDate) {
                    $this->wrestlerRepository->employ($wrestler, $activationDate);
                    $wrestler->save();
                });

                $this->tagTeamRepository->employ($tagTeam, $activationDate);
                $tagTeam->save();
            });
        }

        $this->stableRepository->activate($stable, $activationDate);
        $stable->save();
    }
}
