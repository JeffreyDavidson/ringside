<?php

namespace Tests\Unit\Strategies\Suspension;

use App\Exceptions\CannotBeSuspendedException;
use App\Models\Manager;
use App\Repositories\ManagerRepository;
use App\Strategies\Suspension\ManagerSuspensionStrategy;
use Tests\TestCase;

/**
 * @group managers
 * @group strategies
 */
class ManagerSuspensionStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function a_suspendable_manager_can_be_suspended_without_a_date_passed_in()
    {
        $suspensionDate = null;
        $managerMock = $this->mock(Manager::class);
        $repositoryMock = $this->mock(ManagerRepository::class);
        $strategy = new ManagerSuspensionStrategy($repositoryMock);

        $managerMock->expects()->canBeSuspended()->once()->andReturns(true);
        $repositoryMock->expects()->suspend($managerMock, $suspensionDate)->once()->andReturns($managerMock);

        $strategy->setSuspendable($managerMock)->suspend($suspensionDate);
    }

    /**
     * @test
     */
    public function a_suspendable_manager_can_be_suspended_with_a_given_date()
    {
        $suspensionDate = now()->toDateTimeString();
        $managerMock = $this->mock(Manager::class);
        $repositoryMock = $this->mock(ManagerRepository::class);
        $strategy = new ManagerSuspensionStrategy($repositoryMock);

        $managerMock->expects()->canBeSuspended()->andReturns(true);
        $repositoryMock->expects()->suspend($managerMock, $suspensionDate)->once()->andReturns();

        $strategy->setSuspendable($managerMock)->suspend($suspensionDate);
    }

    /**
     * @test
     */
    public function a_suspendable_manager_that_cannot_be_suspended_throws_an_exception()
    {
        $suspensionDate = null;
        $managerMock = $this->mock(Manager::class);
        $repositoryMock = $this->mock(ManagerRepository::class);
        $strategy = new ManagerSuspensionStrategy($repositoryMock);

        $managerMock->expects()->canBeSuspended()->andReturns(false);
        $repositoryMock->shouldNotReceive('suspend');

        $this->expectException(CannotBeSuspendedException::class);

        $strategy->setSuspendable($managerMock)->suspend($suspensionDate);
    }
}
