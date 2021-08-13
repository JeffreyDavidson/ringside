<?php

namespace App\Services;

use App\Models\Referee;
use App\Repositories\RefereeRepository;
use App\Strategies\ClearInjury\RefereeClearInjuryStrategy;
use App\Strategies\Employment\RefereeEmploymentStrategy;
use App\Strategies\Injure\RefereeInjuryStrategy;
use App\Strategies\Reinstate\RefereeReinstateStrategy;
use App\Strategies\Release\RefereeReleaseStrategy;
use App\Strategies\Retirement\RefereeRetirementStrategy;
use App\Strategies\Suspend\RefereeSuspendStrategy;
use App\Strategies\Unretire\RefereeUnretireStrategy;

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
     * @param  array $data
     * @return \App\Models\Referee $referee
     */
    public function create(array $data)
    {
        $referee = $this->refereeRepository->create($data);

        if (isset($data['started_at'])) {
            app()->make(RefereeEmploymentStrategy::class)->setEmployable($referee)->employ($data['started_at']);
        }

        return $referee;
    }

    /**
     * Update a given referee with given data.
     *
     * @param  \App\Models\Referee $referee
     * @param  array $data
     * @return \App\Models\Referee $referee
     */
    public function update(Referee $referee, array $data)
    {
        $this->refereeRepository->update($referee, $data);

        if (isset($data['started_at'])) {
            $this->employOrUpdateEmployment($referee, $data['started_at']);
        }

        return $referee;
    }

    /**
     * Employ a given referee or update the given referee's employment date.
     *
     * @param  \App\Models\Referee $referee
     * @param  string $employmentDate
     * @return void
     */
    public function employOrUpdateEmployment(Referee $referee, string $employmentDate)
    {
        if ($referee->isNotInEmployment()) {
            return app()->make(RefereeEmploymentStrategy::class)->setEmployable($referee)->employ($employmentDate);
        }

        if ($referee->hasFutureEmployment() && ! $referee->employedOn($employmentDate)) {
            return $this->refereeRepository->updateEmployment($referee, $employmentDate);
        }
    }

    /**
     * Delete a given referee.
     *
     * @param  \App\Models\Referee $referee
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
     * @return void
     */
    public function restore(Referee $referee)
    {
        $this->refereeRepository->restore($referee);
    }

    /**
     * Employ a given referee.
     *
     * @param  \App\Models\Referee $referee
     * @return void
     */
    public function employ(Referee $referee)
    {
        app()->make(RefereeEmploymentStrategy::class)->setEmployable($referee)->employ();
    }

    /**
     * Release a given referee.
     *
     * @param  \App\Models\Referee $referee
     * @return void
     */
    public function release(Referee $referee)
    {
        app()->make(RefereeReleaseStrategy::class)->setReleasable($referee)->release();
    }

    /**
     * Injure a given referee.
     *
     * @param  \App\Models\Referee $referee
     * @return void
     */
    public function injure(Referee $referee)
    {
        app()->make(RefereeInjuryStrategy::class)->setInjurable($referee)->injure();
    }

    /**
     * Clear an injury of a given referee.
     *
     * @param  \App\Models\Referee $referee
     * @return void
     */
    public function clearFromInjury(Referee $referee)
    {
        app()->make(RefereeClearInjuryStrategy::class)->setInjurable($referee)->clearInjury();
    }

    /**
     * Suspend a given referee.
     *
     * @param  \App\Models\Referee $referee
     * @return void
     */
    public function suspend(Referee $referee)
    {
        app()->make(RefereeSuspendStrategy::class)->setSuspendable($referee)->suspend();
    }

    /**
     * Reinstate a given referee.
     *
     * @param  \App\Models\Referee $referee
     * @return void
     */
    public function reinstate(Referee $referee)
    {
        app()->make(RefereeReinstateStrategy::class)->setReinstatable($referee)->reinstate();
    }

    /**
     * Retire a given referee.
     *
     * @param  \App\Models\Referee $referee
     * @return void
     */
    public function retire(Referee $referee)
    {
        app()->make(RefereeRetirementStrategy::class)->setRetirable($referee)->retire();
    }

    /**
     * Unretire a given referee.
     *
     * @param  \App\Models\Referee $referee
     * @return void
     */
    public function unretire(Referee $referee)
    {
        app()->make(RefereeUnretireStrategy::class)->setUnretirable($referee)->unretire();
    }
}
