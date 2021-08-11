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

        if ($data['started_at']) {
            (new RefereeEmploymentStrategy($referee))->employ($data['started_at']);
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

        if ($data['started_at']) {
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
    public function employOrUpdateEmployment(Referee $referee, $employmentDate)
    {
        if ($referee->isNotInEmployment()) {
            (new RefereeEmploymentStrategy($referee))->employ($employmentDate);
        }

        if ($referee->hasFutureEmployment() && $referee->futureEmployment->started_at->ne($employmentDate)) {
            return $referee->futureEmployment()->update(['started_at' => $employmentDate]);
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
     * Clear an injury of a given referee.
     *
     * @param  \App\Models\Referee $referee
     * @return void
     */
    public function clearFromInjury(Referee $referee)
    {
        (new RefereeClearInjuryStrategy($referee))->clearInjury();
    }

    /**
     * Injure a given referee.
     *
     * @param  \App\Models\Referee $referee
     * @return void
     */
    public function injure(Referee $referee)
    {
        (new RefereeInjuryStrategy($referee))->injure();
    }

    /**
     * Employ a given referee.
     *
     * @param  \App\Models\Referee $referee
     * @return void
     */
    public function employ(Referee $referee)
    {
        (new RefereeEmploymentStrategy($referee))->employ();
    }

    /**
     * Release a referee.
     *
     * @param  \App\Models\Referee $referee
     * @return void
     */
    public function release(Referee $referee)
    {
        (new RefereeReleaseStrategy($referee))->release();
    }

    /**
     * Suspend a given referee.
     *
     * @param  \App\Models\Referee $referee
     * @return void
     */
    public function suspend(Referee $referee)
    {
        (new RefereeSuspendStrategy($referee))->suspend();
    }

    /**
     * Reinstate a given referee.
     *
     * @param  \App\Models\Referee $referee
     * @return void
     */
    public function reinstate(Referee $referee)
    {
        (new RefereeReinstateStrategy($referee))->reinstate();
    }

    /**
     * Retire a given referee.
     *
     * @param  \App\Models\Referee $referee
     * @return void
     */
    public function retire(Referee $referee)
    {
        (new RefereeRetirementStrategy($referee))->retire();
    }

    /**
     * Unretire a given referee.
     *
     * @param  \App\Models\Referee $referee
     * @return void
     */
    public function unretire(Referee $referee)
    {
        (new RefereeUnretireStrategy($referee))->unretire();
    }
}
