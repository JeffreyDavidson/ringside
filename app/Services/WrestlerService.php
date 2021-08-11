<?php

namespace App\Services;

use App\Models\Wrestler;
use App\Repositories\WrestlerRepository;
use App\Strategies\ClearInjury\WrestlerClearInjuryStrategy;
use App\Strategies\Employment\WrestlerEmploymentStrategy;
use App\Strategies\Injure\WrestlerInjuryStrategy;
use App\Strategies\Reinstate\WrestlerReinstateStrategy;
use App\Strategies\Release\WrestlerReleaseStrategy;
use App\Strategies\Retirement\WrestlerRetirementStrategy;
use App\Strategies\Suspend\WrestlerSuspendStrategy;
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

        if ($data['started_at']) {
            (new WrestlerEmploymentStrategy($wrestler))->employ($data['started_at']);
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

        if ($data['started_at']) {
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
            return (new WrestlerEmploymentStrategy($wrestler))->employ($employmentDate);
        }

        if ($wrestler->hasFutureEmployment() && $wrestler->futureEmployment->started_at->ne($employmentDate)) {
            return $wrestler->futureEmployment()->update(['started_at' => $employmentDate]);
        }
    }

    /**
     * Clear an injury of a given wrestler.
     *
     * @param  \App\Models\Wrestler $wrestler
     * @return void
     */
    public function clearFromInjury(Wrestler $wrestler)
    {
        (new WrestlerClearInjuryStrategy($wrestler))->clearInjury();
    }

    /**
     * Injure a given wrestler.
     *
     * @param  \App\Models\Wrestler $wrestler
     * @return void
     */
    public function injure(Wrestler $wrestler)
    {
        (new WrestlerInjuryStrategy($wrestler))->injure();
    }

    /**
     * Employ a given wrestler.
     *
     * @param  \App\Models\Wrestler $wrestler
     * @return void
     */
    public function employ(Wrestler $wrestler)
    {
        (new WrestlerEmploymentStrategy($wrestler))->employ();
    }

    /**
     * Unretire a given wrestler.
     *
     * @param  \App\Models\Wrestler $wrestler
     * @return void
     */
    public function suspend(Wrestler $wrestler)
    {
        (new WrestlerSuspendStrategy($wrestler))->suspend();
    }

    /**
     * Reinstate a given wrestler.
     *
     * @param  \App\Models\Wrestler $wrestler
     * @return void
     */
    public function reinstate(Wrestler $wrestler)
    {
        (new WrestlerReinstateStrategy($wrestler))->reinstate();
    }

    /**
     * Retire a given wrestler.
     *
     * @param  \App\Models\Wrestler $wrestler
     * @return void
     */
    public function retire(Wrestler $wrestler)
    {
        (new WrestlerRetirementStrategy($wrestler))->retire();
    }

    /**
     * Unretire a given wrestler.
     *
     * @param  \App\Models\Wrestler $wrestler
     * @return void
     */
    public function unretire(Wrestler $wrestler)
    {
        (new WrestlerUnretireStrategy($wrestler))->unretire();
    }

    /**
     * Release a given wrestler.
     *
     * @param  \App\Models\Wrestler $wrestler
     * @return void
     */
    public function release(Wrestler $wrestler)
    {
        (new WrestlerReleaseStrategy($wrestler))->release();
    }
}
