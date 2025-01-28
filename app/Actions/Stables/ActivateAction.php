<?php

declare(strict_types=1);

namespace App\Actions\Stables;

use App\Actions\Managers\EmployAction as ManagerEmployAction;
use App\Actions\TagTeams\EmployAction as TagTeamEmployAction;
use App\Actions\Wrestlers\EmployAction as WrestlerEmployAction;
use App\Exceptions\CannotBeActivatedException;
use App\Models\Stable;
use App\Models\TagTeam;
use App\Models\Wrestler;
use Illuminate\Support\Carbon;
use Lorisleiva\Actions\Concerns\AsAction;

class ActivateAction extends BaseStableAction
{
    use AsAction;

    /**
     * Activate a stable.
     *
     * @throws \App\Exceptions\CannotBeActivatedException
     */
    public function handle(Stable $stable, ?Carbon $startDate = null): void
    {
        $this->ensureCanBeActivated($stable);

        $startDate ??= now();

        if ($stable->currentWrestlers->isNotEmpty()) {
            $stable->currentWrestlers->each(
                fn (Wrestler $wrestler) => app(WrestlerEmployAction::class)->handle($wrestler, $startDate)
            );
        }

        if ($stable->currentTagTeams->isNotEmpty()) {
            $stable->currentTagTeams->each(
                fn (TagTeam $tagTeam) => app(TagTeamEmployAction::class)->handle($tagTeam, $startDate)
            );
        }

        if ($stable->currentManagers->isNotEmpty()) {
            $stable->currentManagers->each(
                fn (Manager $manager) => app(ManagerEmployAction::class)->handle($manager, $startDate)
            );
        }

        $this->stableRepository->activate($stable, $startDate);
    }

    /**
     * Ensure a stable can be activated.
     *
     * @throws \App\Exceptions\CannotBeActivatedException
     */
    private function ensureCanBeActivated(Stable $stable): void
    {
        if ($stable->isCurrentlyActivated()) {
            throw CannotBeActivatedException::activated();
        }
    }
}
