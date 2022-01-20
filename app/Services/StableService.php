<?php

namespace App\Services;

use App\DataTransferObjects\StableData;
use App\Models\Stable;
use App\Repositories\StableRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class StableService
{
    /**
     * The repository implementation.
     *
     * @var \App\Repositories\StableRepository
     */
    protected $stableRepository;

    /**
     * Create a new stable service instance.
     *
     * @param \App\Repositories\StableRepository $stableRepository
     */
    public function __construct(StableRepository $stableRepository)
    {
        $this->stableRepository = $stableRepository;
    }

    /**
     * Create a stable with given data.
     *
     * @param  \App\DataTransferObjects\StableData $stableData
     *
     * @return \App\Models\Stable $stable
     */
    public function create(StableData $stableData)
    {
        $stable = $this->stableRepository->create($stableData);

        if (isset($stableData->start_date)) {
            $this->stableRepository->activate($stable, $stableData->start_date);
        }

        $this->addMembers($stable, $stableData->wrestlers, $stableData->tagTeams);

        return $stable;
    }

    /**
     * Update a given stable with given data.
     *
     * @param  \App\Models\Stable $stable
     * @param  \App\DataTransferObjects\StableData $stableData
     *
     * @return \App\Models\Stable $stable
     */
    public function update(Stable $stable, StableData $stableData)
    {
        $this->stableRepository->update($stable, $stableData);

        if (isset($stableData->start_date)) {
            $this->activateOrUpdateActivation($stable, $stableData->start_date);
        }

        $this->updateMembers($stable, $stableData->wrestlers, $stableData->tagTeams);

        return $stable;
    }

    /**
     * Delete a given stable.
     *
     * @param  \App\Models\Stable $stable
     *
     * @return void
     */
    public function delete(Stable $stable)
    {
        $this->stableRepository->delete($stable);
    }

    /**
     * Restore a given stable.
     *
     * @param  \App\Models\Stable $stable
     *
     * @return void
     */
    public function restore(Stable $stable)
    {
        $this->stableRepository->restore($stable);
    }

    /**
     * Add members to a given stable.
     *
     * @param  \App\Models\Stable $stable
     * @param  \Illuminate\Support\Collection|null $wrestlers
     * @param  \Illuminate\Support\Collection|null $tagTeams
     * @param  \Carbon\Carbon|null $joinedDate
     *
     * @return \App\Models\Stable $stable
     */
    private function addMembers(
        Stable $stable,
        Collection $wrestlers = null,
        Collection $tagTeams = null,
        Carbon $joinedDate = null
    ) {
        $joinedDate ??= now();

        if ($wrestlers) {
            $this->stableRepository->addWrestlers($stable, $wrestlers, $joinedDate);
        }

        if ($tagTeams) {
            $this->stableRepository->addTagTeams($stable, $tagTeams, $joinedDate);
        }

        return $stable;
    }

    /**
     * Update the members of a given stable.
     *
     * @param  \App\Models\Stable $stable
     * @param  \Illuminate\Support\Collection $wrestlers
     * @param  \Illuminate\Support\Collection $tagTeams
     *
     * @return \App\Models\Stable $stable
     */
    private function updateMembers(Stable $stable, Collection $wrestlers, Collection $tagTeams)
    {
        $now = now();

        if ($stable->currentWrestlers->isEmpty()) {
            $this->stableRepository->addWrestlers($stable, $wrestlers, $now);
        } else {
            $currentWrestlers = $stable->currentWrestlers;
            $formerWrestlers = $currentWrestlers->diff($wrestlers);
            $newWrestlers = $wrestlers->diff($currentWrestlers);

            $this->stableRepository->removeWrestlers($stable, $formerWrestlers, $now);
            $this->stableRepository->addWrestlers($stable, $newWrestlers, $now);
        }

        if ($stable->currentTagTeams->isEmpty()) {
            $this->stableRepository->addTagTeams($stable, $tagTeams, $now);
        } else {
            $currentTagTeams = $stable->currentTagTeams;
            $formerTagTeams = $currentTagTeams->diff($tagTeams);
            $newTagTeams = $wrestlers->diff($currentTagTeams);

            $this->stableRepository->removeTagTeams($stable, $formerTagTeams, $now);
            $this->stableRepository->addTagTeams($stable, $newTagTeams, $now);
        }

        return $stable;
    }

    /**
     * Update the activation start date of a given stable.
     *
     * @param  \App\Models\Stable $stable
     * @param  \Carbon\Carbon $activationDate
     *
     * @return \App\Models\Stable $stable
     */
    public function activateOrUpdateActivation(Stable $stable, Carbon $activationDate)
    {
        if ($stable->isNotInActivation()) {
            return $this->stableRepository->activate($stable, $activationDate);
        }

        if ($stable->hasFutureActivation() && ! $stable->activatedOn($activationDate)) {
            return $this->stableRepository->activate($stable, $activationDate);
        }

        return $stable;
    }

    /**
     * Add given tag teams to a given stable on a given join date.
     *
     * @param  \App\Models\Stable $stable
     * @param  array $tagTeamIds
     * @param  string $joinedDate
     *
     * @return void
     */
    public function addTagTeams($stable, $tagTeamIds, $joinedDate)
    {
        foreach ($tagTeamIds as $tagTeamId) {
            $stable->tagTeams()->attach($tagTeamId, ['joined_at' => $joinedDate]);
        }
    }

    /**
     * Update the wrestlers of the stable.
     *
     * @param  \App\Models\Stable  $stable
     * @param  \Illuminate\Support\Collection  $wrestlers
     *
     * @return void
     */
    public function updateWrestlers(Stable $stable, Collection $wrestlers)
    {
        $now = now();

        if ($stable->currentWrestlers->isEmpty()) {
            $this->stableRepository->addWrestlers($stable, $wrestlers, $now);
        } else {
            $currentWrestlers = $stable->currentWrestlers;
            $formerWrestlers = $currentWrestlers->diff($wrestlers);
            $newWrestlers = $wrestlers->diff($currentWrestlers);

            $this->stableRepository->removeWrestlers($stable, $formerWrestlers, $now);
            $this->stableRepository->addWrestlers($stable, $newWrestlers, $now);
        }
    }
}
