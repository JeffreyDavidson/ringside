<?php

namespace App\Services;

use App\Models\Stable;
use App\Repositories\StableRepository;

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
     * @param  array $data
     * @return \App\Models\Stable $stable
     */
    public function create(array $data)
    {
        $stable = $this->stableRepository->create($data);

        if (isset($data['started_at'])) {
            $this->stableRepository->activate($stable, $data['started_at']);
        }

        $this->addMembers($stable, $data['wrestlers'], $data['tag_teams']);

        return $stable;
    }

    /**
     * Update a given stable with given data.
     *
     * @param  \App\Models\Stable $stable
     * @param  array $data
     * @return \App\Models\Stable $stable
     */
    public function update(Stable $stable, array $data)
    {
        $this->stableRepository->update($stable, $data);

        if (isset($data['started_at'])) {
            $this->activateOrUpdateActivation($stable, $data['started_at']);
        }

        $this->updateMembers($stable, $data['wrestlers'], $data['tag_teams']);

        return $stable;
    }

    /**
     * Delete a given stable.
     *
     * @param  \App\Models\Stable $stable
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
     * @param  array|null $wrestlerIds
     * @param  array|null $tagTeamIds
     * @param  string|null $joinedDate
     * @return \App\Models\Stable $stable
     */
    private function addMembers(Stable $stable, array $wrestlerIds = null, array $tagTeamIds = null, string $joinedDate = null)
    {
        $joinedDate ??= now();

        if ($wrestlerIds) {
            $stable->addWrestlers($wrestlerIds, $joinedDate);
        }

        if ($tagTeamIds) {
            $stable->addTagTeams($tagTeamIds, $joinedDate);
        }

        return $stable;
    }

    /**
     * Update the members of a given stable.
     *
     * @param  \App\Models\Stable $stable
     * @param  array $wrestlerIds
     * @param  array $tagTeamIds
     * @return \App\Models\Stable $stable
     */
    private function updateMembers(Stable $stable, array $wrestlerIds, array $tagTeamIds)
    {
        if ($stable->currentWrestlers->isEmpty()) {
            if ($wrestlerIds) {
                foreach ($wrestlerIds as $wrestlerId) {
                    $stable->currentWrestlers()->attach($wrestlerId, ['joined_at' => now()]);
                }
            }
        } else {
            $currentWrestlerIds = collect($stable->currentWrestlers->modelKeys());
            $suggestedWrestlerIds = collect($wrestlerIds);
            $formerWrestlerIds = $currentWrestlerIds->diff($suggestedWrestlerIds);
            $newWrestlerIds = $suggestedWrestlerIds->diff($currentWrestlerIds);

            $now = now();

            foreach ($formerWrestlerIds as $formerWrestlerId) {
                $stable->currentWrestlers()->updateExistingPivot($formerWrestlerId, ['left_at' => $now]);
            }

            foreach ($newWrestlerIds as $newWrestlerId) {
                $stable->currentWrestlers()->attach($newWrestlerId, ['joined_at' => $now]);
            }
        }

        if ($stable->currentTagTeams->isEmpty()) {
            if ($tagTeamIds) {
                foreach ($tagTeamIds as $tagTeamId) {
                    $stable->currentTagTeams()->attach($tagTeamId, ['joined_at' => now()]);
                }
            }
        } else {
            $currentTagTeamIds = collect($stable->currentTagTeams->modelKeys());
            $suggestedTagTeamIds = collect($tagTeamIds);
            $formerTagTeamIds = $currentTagTeamIds->diff($suggestedTagTeamIds);
            $newTagTeamIds = $suggestedTagTeamIds->diff($currentTagTeamIds);

            $now = now();

            foreach ($formerTagTeamIds as $formerTagTeamId) {
                $stable->currentTagTeams()->updateExistingPivot($formerTagTeamId, ['left_at' => $now]);
            }

            foreach ($newTagTeamIds as $newTagTeamId) {
                $stable->currentTagTeams()->attach($newTagTeamId, ['joined_at' => $now]);
            }
        }

        return $stable;
    }

    /**
     * Update the activation start date of a given stable.
     *
     * @param  \App\Models\Stable $stable
     * @param  string $activationDate
     * @return \App\Models\Stable $stable
     */
    public function activateOrUpdateActivation(Stable $stable, string $activationDate)
    {
        if ($stable->isNotInActivation()) {
            return $this->stableRepository->activate($stable, $activationDate);
        }

        if ($stable->hasFutureActivation() && ! $stable->activatedOn($activationDate)) {
            return $this->stableRepository->updateActivation($stable, $activationDate);
        }

        return $stable;
    }

    /**
     * Add given wrestlers to a given stable on a given join date.
     *
     * @param  \App\Models\Stable $stable
     * @param  array $wrestlerIds
     * @param  string $joinedDate
     * @return void
     */
    public function addWrestlers(Stable $stable, $wrestlerIds, $joinedDate)
    {
        foreach ($wrestlerIds as $wrestlerId) {
            $stable->wrestlers()->attach($wrestlerId, ['joined_at' => $joinedDate]);
        }
    }

    /**
     * Add given tag teams to a given stable on a given join date.
     *
     * @param  \App\Models\Stable $stable
     * @param  array $tagTeamIds
     * @param  string $joinedDate
     * @return void
     */
    public function addTagTeams($stable, $tagTeamIds, $joinedDate)
    {
        foreach ($tagTeamIds as $tagTeamId) {
            $stable->tagTeams()->attach($tagTeamId, ['joined_at' => $joinedDate]);
        }
    }
}
