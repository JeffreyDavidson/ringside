<?php

namespace App\Actions\Stables;

use App\Models\Stable;
use Illuminate\Database\Eloquent\Collection;
use Lorisleiva\Actions\Concerns\AsAction;

class AddMembersAction extends BaseStableAction
{
    use AsAction;

    /**
     * Add members to a given stable.
     *
     * @param  \App\Models\Stable  $stable
     * @param  \Illuminate\Database\Eloquent\Collection  $wrestlers
     * @param  \Illuminate\Database\Eloquent\Collection  $tagTeams
     *
     * @return void
     */
    public function handle(Stable $stable, Collection $wrestlers, Collection $tagTeams): void
    {
        $joinedDate ??= now();

        if ($wrestlers) {
            $this->stableRepository->addWrestlers($stable, $wrestlers, $joinedDate);
        }

        if ($tagTeams) {
            $this->stableRepository->addTagTeams($stable, $tagTeams, $joinedDate);
        }
    }
}
