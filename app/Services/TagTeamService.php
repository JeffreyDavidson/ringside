<?php

namespace App\Services;

use App\Models\TagTeam;
use App\Repositories\TagTeamRepository;
use App\Strategies\Employment\TagTeamEmploymentStrategy;
use App\Strategies\Employment\WrestlerEmploymentStrategy;
use App\Strategies\Reinstate\TagTeamReinstateStrategy;
use App\Strategies\Release\TagTeamReleaseStrategy;
use App\Strategies\Retirement\TagTeamRetirementStrategy;
use App\Strategies\Suspend\TagTeamSuspendStrategy;
use App\Strategies\Unretire\TagTeamUnretireStrategy;

class TagTeamService
{
    /**
     * The repository implementation.
     *
     * @var \App\Repositories\TagTeamRepository
     */
    protected $tagTeamRepository;

    /**
     * Create a new tag team service instance.
     *
     * @param \App\Repositories\TagTeamRepository $tagTeamRepository
     */
    public function __construct(TagTeamRepository $tagTeamRepository)
    {
        $this->tagTeamRepository = $tagTeamRepository;
    }

    /**
     * Create a tag team with given data.
     *
     * @param  array $data
     * @return \App\Models\TagTeam $tagTeam
     */
    public function create(array $data)
    {
        $tagTeam = $this->tagTeamRepository->create($data);

        if (isset($data['started_at'])) {
            app()->make(TagTeamEmploymentStrategy::class)->setEmployable($tagTeam)->employ($data['started_at']);

            $this->tagTeamRepository->addWrestlers($tagTeam, $data['wrestlers'], $data['started_at']);
        } else {
            if (isset($data['wrestlers'])) {
                $this->tagTeamRepository->addWrestlers($tagTeam, $data['wrestlers']);
            }
        }

        return $tagTeam;
    }

    /**
     * Update a given tag team with given data.
     *
     * @param  \App\Models\TagTeam $tagTeam
     * @param  array $data
     * @return \App\Models\TagTeam $tagTeam
     */
    public function update(TagTeam $tagTeam, array $data)
    {
        $this->tagTeamRepository->update($tagTeam, $data);

        if (isset($data['started_at'])) {
            $this->employOrUpdateEmployment($tagTeam, $data['started_at']);
        } else {
            if (isset($data['wrestlers'])) {
                $this->updateTagTeamPartners($tagTeam, $data['wrestlers']);
            }
        }

        return $tagTeam;
    }

    /**
     * Employ a given tag team or update the given tag team's employment date.
     *
     * @param  \App\Models\TagTeam $tagTeam
     * @param  string $employmentDate
     * @return \App\Models\TagTeam $tagTeam
     */
    public function employOrUpdateEmployment(TagTeam $tagTeam, string $employmentDate)
    {
        if ($tagTeam->isNotInEmployment()) {
            return app()->make(TagTeamEmploymentStrategy::class)->setEmployable($tagTeam)->employ($employmentDate);
        }

        if ($tagTeam->hasFutureActivation() && ! $tagTeam->employedOn($employmentDate)) {
            return $this->tagTeamRepository->updateEmployment($tagTeam, $employmentDate);
        }
    }

    /**
     * Delete a given tag team.
     *
     * @param  \App\Models\TagTeam $tagTeam
     * @return void
     */
    public function delete(TagTeam $tagTeam)
    {
        $this->tagTeamRepository->delete($tagTeam);
    }

    /**
     * Restore a given tag team.
     *
     * @param  \App\Models\TagTeam $tagTeam
     * @return void
     */
    public function restore(TagTeam $tagTeam)
    {
        $this->tagTeamRepository->restore($tagTeam);
    }

    /**
     * Update a given tag team with given wrestlers.
     *
     * @param  \App\Models\TagTeam $tagTeam
     * @param  array $wrestlerIds
     * @return \App\Models\TagTeam $tagTeam
     */
    public function updateTagTeamPartners(TagTeam $tagTeam, array $wrestlerIds)
    {
        if ($tagTeam->currentWrestlers->isEmpty()) {
            if ($wrestlerIds) {
                $this->tagTeamRepository->addWrestlers($tagTeam, $wrestlerIds);
            }
        } else {
            $currentTagTeamPartners = collect($tagTeam->currentWrestlers->modelKeys());
            $suggestedTagTeamPartners = collect($wrestlerIds);
            $formerTagTeamPartners = $currentTagTeamPartners->diff($suggestedTagTeamPartners);
            $newTagTeamPartners = $suggestedTagTeamPartners->diff($currentTagTeamPartners);

            $this->tagTeamRepository->syncTagTeamPartners($tagTeam, $formerTagTeamPartners, $newTagTeamPartners);
        }

        return $tagTeam;
    }

    /**
     * Employ a given tag team.
     *
     * @param  \App\Models\TagTeam $tagTeam
     * @return void
     */
    public function employ(TagTeam $tagTeam)
    {
        app()->make(TagTeamEmploymentStrategy::class)->setEmployable($tagTeam)->employ();
    }
}
