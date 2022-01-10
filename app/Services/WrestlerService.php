<?php

namespace App\Services;

use App\DataTransferObjects\WrestlerData;
use App\Models\Wrestler;
use App\Repositories\WrestlerRepository;
use Carbon\Carbon;

class WrestlerService
{
    /**
     * The repository implementation.
     *
     * @var \App\Repositories\WrestlerRepository
     */
    protected $wrestlerRepository;

    /**
     * Create a new wrestler service instance.
     *
     * @param \App\Repositories\WrestlerRepository $wrestlerRepository
     */
    public function __construct(WrestlerRepository $wrestlerRepository)
    {
        $this->wrestlerRepository = $wrestlerRepository;
    }

    /**
     * Create a new wrestler with given data.
     *
     * @param  \App\DataTransferObjects\WrestlerData $wrestlerData
     *
     * @return \App\Models\Wrestler $wrestler
     */
    public function create(WrestlerData $wrestlerData)
    {
        $wrestler = $this->wrestlerRepository->create($wrestlerData);

        if (isset($wrestlerData->start_date)) {
            $this->wrestlerRepository->employ($wrestler, $wrestlerData->start_date);
        }

        return $wrestler;
    }

    /**
     * Update a given wrestler with given data.
     *
     * @param  \App\Models\Wrestler $wrestler
     * @param  \App\DataTransferObjects\WrestlerData $wrestlerData
     *
     * @return \App\Models\Wrestler $wrestler
     */
    public function update(Wrestler $wrestler, WrestlerData $wrestlerData)
    {
        $this->wrestlerRepository->update($wrestler, $wrestlerData);

        if ($wrestler->canHaveEmploymentStartDateChanged() && isset($wrestlerData->start_date)) {
            $this->employOrUpdateEmployment($wrestler, $wrestlerData->start_date);
        }

        return $wrestler;
    }

    /**
     * Employ a given wrestler or update the given wrestler's employment date.
     *
     * @param  \App\Models\Wrestler $wrestler
     * @param  \Carbon\Carbon $employmentDate
     *
     * @return \App\Models\Wrestler $wrestler
     */
    public function employOrUpdateEmployment(Wrestler $wrestler, Carbon $employmentDate)
    {
        if ($wrestler->isNotInEmployment()) {
            $this->wrestlerRepository->employ($wrestler, $employmentDate);

            return $wrestler;
        }

        if ($wrestler->hasFutureEmployment() && ! $wrestler->employedOn($employmentDate)) {
            $this->wrestlerRepository->updateEmployment($wrestler, $employmentDate);

            return $wrestler;
        }

        return $wrestler;
    }

    /**
     * Delete a given wrestler.
     *
     * @param  \App\Models\Wrestler $wrestler
     *
     * @return void
     */
    public function delete(Wrestler $wrestler)
    {
        $this->wrestlerRepository->delete($wrestler);
    }

    /**
     * Restore a given wrestler.
     *
     * @param  \App\Models\Wrestler $wrestler
     *
     * @return void
     */
    public function restore(Wrestler $wrestler)
    {
        $this->wrestlerRepository->restore($wrestler);
    }
}
