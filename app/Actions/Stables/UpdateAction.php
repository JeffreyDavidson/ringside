<?php

declare(strict_types=1);

namespace App\Actions\Stables;

use App\Data\StableData;
use App\Models\Stable;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateAction extends BaseStableAction
{
    use AsAction;

    /**
     * Update a stable.
     */
    public function handle(Stable $stable, StableData $stableData): Stable
    {
        $this->stableRepository->update($stable, $stableData);

        if (isset($stableData->start_date) && $this->ensureStartDateCanBeChanged($stable)) {
            app(ActivateAction::class)->handle($stable, $stableData->start_date);
        }

        app(UpdateMembersAction::class)->handle(
            $stable,
            $stableData->wrestlers,
            $stableData->tagTeams,
            $stableData->managers
        );

        return $stable;
    }

    /**
     * Ensure a stable's start date can be changed.
     */
    private function ensureStartDateCanBeChanged(Stable $stable): bool
    {
        if ($stable->isUnactivated() || $stable->hasFutureActivation()) {
            return true;
        }

        // Add check on start date from request

        return false;
    }
}
