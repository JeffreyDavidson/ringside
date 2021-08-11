<?php

namespace App\Services;

use App\Models\Manager;
use App\Repositories\ManagerRepository;
use App\Strategies\ClearInjury\ManagerClearInjuryStrategy;
use App\Strategies\Employment\ManagerEmploymentStrategy;
use App\Strategies\Injure\ManagerInjuryStrategy;
use App\Strategies\Reinstate\ManagerReinstateStrategy;
use App\Strategies\Release\ManagerReleaseStrategy;
use App\Strategies\Retirement\ManagerRetirementStrategy;
use App\Strategies\Suspend\ManagerSuspendStrategy;
use App\Strategies\Unretire\ManagerUnretireStrategy;

class ManagerService
{
    /**
     * The repository implementation.
     *
     * @var \App\Repositories\ManagerRepository
     */
    protected $managerRepository;

    /**
     * Create a new manager service instance.
     *
     * @param \App\Repositories\ManagerRepository $managerRepository
     */
    public function __construct(ManagerRepository $managerRepository)
    {
        $this->managerRepository = $managerRepository;
    }

    /**
     * Create a manager with given data.
     *
     * @param  array $data
     * @return \App\Models\Manager $manager
     */
    public function create(array $data)
    {
        $manager = $this->managerRepository->create($data);

        if (isset($data['started_at'])) {
            (new ManagerEmploymentStrategy($manager))->employ($data['started_at']);
        }

        return $manager;
    }

    /**
     * Update a given manager with given data.
     *
     * @param  \App\Models\Manager $manager
     * @param  array $data
     * @return \App\Models\Manager $manager
     */
    public function update(Manager $manager, array $data)
    {
        $this->managerRepository->update($manager, $data);

        if ($data['started_at']) {
            $this->employOrUpdateEmployment($manager, $data['started_at']);
        }

        return $manager;
    }

    /**
     * Employ a given manager or update the given manager's employment date.
     *
     * @param  \App\Models\Manager $manager
     * @param  string $employmentDate
     * @return void
     */
    public function employOrUpdateEmployment(Manager $manager, $employmentDate)
    {
        if ($manager->isNotInEmployment()) {
            (new ManagerEmploymentStrategy($manager))->employ($employmentDate);
        }

        if ($manager->hasFutureEmployment() && $manager->futureEmployment->started_at->ne($employmentDate)) {
            return $manager->futureEmployment()->update(['started_at' => $employmentDate]);
        }
    }

    /**
     * Delete a given manager.
     *
     * @param  \App\Models\Manager $manager
     * @return void
     */
    public function delete(Manager $manager)
    {
        $this->managerRepository->delete($manager);
    }

    /**
     * Restore a given manager.
     *
     * @param  \App\Models\Manager $manager
     * @return void
     */
    public function restore(Manager $manager)
    {
        $this->managerRepository->restore($manager);
    }

    /**
     * Clear an injury of a given manager.
     *
     * @param  \App\Models\Manager $manager
     * @return void
     */
    public function clearFromInjury(Manager $manager)
    {
        (new ManagerClearInjuryStrategy($manager))->clearInjury();
    }

    /**
     * Injure a given manager.
     *
     * @param  \App\Models\Manager $manager
     * @return void
     */
    public function injure(Manager $manager)
    {
        (new ManagerInjuryStrategy($manager))->injure();
    }

    /**
     * Employ a given manager.
     *
     * @param  \App\Models\Manager $manager
     * @return void
     */
    public function employ(Manager $manager)
    {
        (new ManagerEmploymentStrategy($manager))->employ();
    }

    /**
     * Reinstate a given manager.
     *
     * @param  \App\Models\Manager $manager
     * @return void
     */
    public function reinstate(Manager $manager)
    {
        (new ManagerReinstateStrategy($manager))->reinstate();
    }

    /**
     * Unretire a given manager.
     *
     * @param  \App\Models\Manager $manager
     * @return void
     */
    public function unretire(Manager $manager)
    {
        (new ManagerUnretireStrategy($manager))->unretire();
    }

    /**
     * Suspend a manager.
     *
     * @param  \App\Models\Manager $manager
     * @return void
     */
    public function suspend(Manager $manager)
    {
        (new ManagerSuspendStrategy($manager))->suspend();
    }

    /**
     * Retire a given manager.
     *
     * @param  \App\Models\Manager $manager
     * @return void
     */
    public function retire(Manager $manager)
    {
        (new ManagerRetirementStrategy($manager))->retire();
    }

    /**
     * Release a given manager.
     *
     * @param  \App\Models\Manager $manager
     * @return void
     */
    public function release(Manager $manager)
    {
        (new ManagerReleaseStrategy($manager))->release();
    }
}
