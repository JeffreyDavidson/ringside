<?php

namespace App\Services;

use App\DataTransferObjects\TagTeamData;
use App\Models\TagTeam;
use App\Models\Wrestler;
use App\Repositories\TagTeamRepository;
use App\Repositories\WrestlerRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class TagTeamService
{
    /**
     * The repository implementation.
     *
     * @var \App\Repositories\TagTeamRepository
     */
    protected $tagTeamRepository;

    /**
     * The repository implementation.
     *
     * @var \App\Repositories\WrestlerRepository
     */
    protected $wrestlerRepository;

    /**
     * Create a new tag team service instance.
     *
     * @param \App\Repositories\TagTeamRepository $tagTeamRepository
     * @param \App\Repositories\WrestlerRepository $wrestlerRepository
     */
    public function __construct(TagTeamRepository $tagTeamRepository, WrestlerRepository $wrestlerRepository)
    {
        $this->tagTeamRepository = $tagTeamRepository;
        $this->wrestlerRepository = $wrestlerRepository;
    }

    /**
     * Create a tag team with given data.
     *
     * @param  \App\DataTransferObjects\TagTeamData $tagTeamData
     *
     * @return \App\Models\TagTeam $tagTeam
     */
    public function create(TagTeamData $tagTeamData)
    {
        $tagTeam = $this->tagTeamRepository->create($tagTeamData);

        if (isset($tagTeamData->start_date)) {
            $this->tagTeamRepository->employ($tagTeam, $tagTeamData->start_date);

            $tagTeamData->wrestlers->map(
                fn (Wrestler $wrestler) => $this->wrestlerRepository->employ($wrestler, $tagTeamData->start_date)
            );

            $this->tagTeamRepository->addWrestlers($tagTeam, $tagTeamData->wrestlers, $tagTeamData->start_date);
        } else {
            if (isset($tagTeamData->wrestlers)) {
                $this->tagTeamRepository->addWrestlers($tagTeam, $tagTeamData->wrestlers);
            }
        }

        return $tagTeam;
    }

    /**
     * Update a given tag team with given data.
     *
     * @param  \App\Models\TagTeam $tagTeam
     * @param  \App\DataTransferObjects\TagTeamData $tagTeamData
     *
     * @return \App\Models\TagTeam $tagTeam
     */
    public function update(TagTeam $tagTeam, TagTeamData $tagTeamData)
    {
        $this->tagTeamRepository->update($tagTeam, $tagTeamData);

        if ($tagTeam->canHaveEmploymentStartDateChanged() && isset($tagTeamData->start_date)) {
            $this->employOrUpdateEmployment($tagTeam, $$tagTeamData->start_date);
        }

        $this->updateTagTeamPartners($tagTeam, $tagTeamData->wrestlers);

        return $tagTeam;
    }

    /**
     * Employ a given tag team or update the given tag team's employment date.
     *
     * @param  \App\Models\TagTeam $tagTeam
     * @param  \Carbon\Carbon $employmentDate
     *
     * @return \App\Models\TagTeam $tagTeam
     */
    public function employOrUpdateEmployment(TagTeam $tagTeam, Carbon $employmentDate)
    {
        if ($tagTeam->isNotInEmployment()) {
            $this->tagTeamRepository->employ($tagTeam, $employmentDate);

            return $tagTeam;
        }

        if ($tagTeam->hasFutureEmployment() && ! $tagTeam->scheduledToBeEmployedOn($employmentDate)) {
            $this->tagTeamRepository->updateEmployment($tagTeam, $employmentDate);

            return $tagTeam;
        }

        return $tagTeam;
    }

    /**
     * Delete a given tag team.
     *
     * @param  \App\Models\TagTeam $tagTeam
     *
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
     *
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
     * @param  \Illuminate\Support\Collection $wrestlers
     *
     * @return \App\Models\TagTeam $tagTeam
     */
    public function updateTagTeamPartners(TagTeam $tagTeam, Collection $wrestlers)
    {
        if ($tagTeam->currentWrestlers->isEmpty()) {
            if ($wrestlers->isNotEmpty()) {
                $this->tagTeamRepository->addWrestlers($tagTeam, $wrestlers);
            }
        } else {
            $currentTagTeamPartners = $tagTeam->currentWrestlers->pluck('id');
            $formerTagTeamPartners = $currentTagTeamPartners->diff($wrestlers);
            $newTagTeamPartners = $wrestlers->diff($currentTagTeamPartners);

            $this->tagTeamRepository->syncTagTeamPartners($tagTeam, $formerTagTeamPartners, $newTagTeamPartners);
        }

        return $tagTeam;
    }

    /**
     * Employ a given tag team.
     *
     * @param  \App\Models\TagTeam $tagTeam
     *
     * @return void
     */
    public function employ(TagTeam $tagTeam)
    {
        $this->tagTeamRepository->employ($tagTeam, now());
    }
}
