<?php

namespace App\Services;

use App\DataTransferObjects\RefereeData;
use App\Models\Referee;
use App\Repositories\RefereeRepository;
use Carbon\Carbon;

class RefereeService
{
    /**
     * The repository implementation.
     *
     * @var \App\Repositories\RefereeRepository
     */
    protected $refereeRepository;

    /**
     * Create a new referee service instance.
     *
     * @param \App\Repositories\RefereeRepository $refereeRepository
     */
    public function __construct(RefereeRepository $refereeRepository)
    {
        $this->refereeRepository = $refereeRepository;
    }

    /**
     * Create a referee with given data.
     *
     * @param  \App\DataTransferObjects\RefereeData $refereeData
     *
     * @return \App\Models\Referee $referee
     */
    public function create(RefereeData $refereeData)
    {
        $referee = $this->refereeRepository->create($refereeData);

        if (isset($refereeData->start_date)) {
            $this->refereeRepository->employ($referee, $refereeData->start_date);
        }

        return $referee;
    }

    /**
     * Update a given referee with given data.
     *
     * @param  \App\Models\Referee $referee
     * @param  \App\DataTransferObjects\RefereeData $refereeData
     *
     * @return \App\Models\Referee $referee
     */
    public function update(Referee $referee, RefereeData $refereeData)
    {
        $this->refereeRepository->update($referee, $refereeData);

        if ($referee->canHaveEmploymentStartDateChanged() && isset($refereeData->start_date)) {
            $this->employOrUpdateEmployment($referee, $refereeData->start_date);
        }

        return $referee;
    }

    /**
     * Employ a given referee or update the given referee's employment date.
     *
     * @param  \App\Models\Referee $referee
     * @param  \Carbon\Carbon $employmentDate
     *
     * @return \App\Models\Referee $referee
     */
    public function employOrUpdateEmployment(Referee $referee, Carbon $employmentDate)
    {
        if ($referee->isNotInEmployment()) {
            $this->refereeRepository->employ($referee, $employmentDate);

            return $referee;
        }

        if ($referee->hasFutureEmployment() && ! $referee->employedOn($employmentDate)) {
            $this->refereeRepository->updateEmployment($referee, $employmentDate);

            return $referee;
        }

        return $referee;
    }

    /**
     * Delete a given referee.
     *
     * @param  \App\Models\Referee $referee
     *
     * @return void
     */
    public function delete(Referee $referee)
    {
        $this->refereeRepository->delete($referee);
    }

    /**
     * Restore a given referee.
     *
     * @param  \App\Models\Referee $referee
     *
     * @return void
     */
    public function restore(Referee $referee)
    {
        $this->refereeRepository->restore($referee);
    }
}
