<?php

namespace App\Services;

use App\Models\Wrestler;
use App\Repositories\WrestlerRepository;
use App\Strategies\ClearInjury\WrestlerClearInjuryStrategy;
use App\Strategies\Employment\WrestlerEmploymentStrategy;
use App\Strategies\Injury\WrestlerInjuryStrategy;
use App\Strategies\Reinstate\WrestlerReinstateStrategy;
use App\Strategies\Release\WrestlerReleaseStrategy;
use App\Strategies\Retirement\WrestlerRetirementStrategy;
use App\Strategies\Suspension\WrestlerSuspensionStrategy;
use App\Strategies\Unretire\WrestlerUnretireStrategy;

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
     * @param  array $data
     * @return \App\Models\Wrestler $wrestler
     */
    public function create(array $data)
    {
        $wrestler = $this->wrestlerRepository->create($data);

        if (isset($data['started_at'])) {
            app()->make(WrestlerEmploymentStrategy::class)->setEmployable($wrestler)->employ($data['started_at']);
        }

        return $wrestler;
    }

    /**
     * Update a given wrestler with given data.
     *
     * @param  \App\Models\Wrestler $wrestler
     * @param  array $data
     * @return \App\Models\Wrestler $wrestler
     */
    public function update(Wrestler $wrestler, array $data)
    {
        $this->wrestlerRepository->update($wrestler, $data);

        if (isset($data['started_at'])) {
            $this->employOrUpdateEmployment($wrestler, $data['started_at']);
        }

        return $wrestler;
    }

    /**
     * Employ a given wrestler or update the given wrestler's employment date.
     *
     * @param  \App\Models\Wrestler $wrestler
     * @param  string $employmentDate
     * @return void
     */
    public function employOrUpdateEmployment(Wrestler $wrestler, string $employmentDate)
    {
        if ($wrestler->isNotInEmployment()) {
            return app()->make(WrestlerEmploymentStrategy::class)->setEmployable($wrestler)->employ($employmentDate);
        }

        if ($wrestler->hasFutureEmployment() && ! $wrestler->employedOn($employmentDate)) {
            return $this->wrestlerRepository->updateEmployment($wrestler, $employmentDate);
        }
    }

    /**
     * Delete a given wrestler.
     *
     * @param  \App\Models\Wrestler $wrestler
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
     * @return void
     */
    public function restore(Wrestler $wrestler)
    {
        $this->wrestlerRepository->restore($wrestler);
    }

    /**
     * Employ a given wrestler.
     *
     * @param  \App\Models\Wrestler $wrestler
     * @return void
     */
    public function employ(Wrestler $wrestler)
    {
        app()->make(WrestlerEmploymentStrategy::class)->setEmployable($wrestler)->employ();
    }

    /**
     * Release a given wrestler.
     *
     * @param  \App\Models\Wrestler $wrestler
     * @return void
     */
    public function release(Wrestler $wrestler)
    {
        app()->make(WrestlerReleaseStrategy::class)->setReleasable($wrestler)->release();
    }

    /**
     * Injure a given wrestler.
     *
     * @param  \App\Models\Wrestler $wrestler
     * @return void
     */
    public function injure(Wrestler $wrestler)
    {
        app()->make(WrestlerInjuryStrategy::class)->setInjurable($wrestler)->injure();
    }

    /**
     * Clear an injury of a given wrestler.
     *
     * @param  \App\Models\Wrestler $wrestler
     * @return void
     */
    public function clearFromInjury(Wrestler $wrestler)
    {
        app()->make(WrestlerClearInjuryStrategy::class)->setInjurable($wrestler)->clearInjury();
    }

    /**
     * Unretire a given wrestler.
     *
     * @param  \App\Models\Wrestler $wrestler
     * @return void
     */
    public function suspend(Wrestler $wrestler)
    {
        app()->make(WrestlerSuspensionStrategy::class)->setSuspendable($wrestler)->suspend();
    }

    /**
     * Reinstate a given wrestler.
     *
     * @param  \App\Models\Wrestler $wrestler
     * @return void
     */
    public function reinstate(Wrestler $wrestler)
    {
        app()->make(WrestlerReinstateStrategy::class)->setReinstatable($wrestler)->reinstate();
    }

    /**
     * Retire a given wrestler.
     *
     * @param  \App\Models\Wrestler $wrestler
     * @return void
     */
    public function retire(Wrestler $wrestler)
    {
        app()->make(WrestlerRetirementStrategy::class)->setRetirable($wrestler)->retire();
    }

    /**
     * Unretire a given wrestler.
     *
     * @param  \App\Models\Wrestler $wrestler
     * @return void
     */
    public function unretire(Wrestler $wrestler)
    {
        app()->make(WrestlerUnretireStrategy::class)->setUnretirable($wrestler)->unretire();
    }
}
