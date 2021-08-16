<?php

namespace Tests\Unit\Strategies\Retirement;

use App\Exceptions\CannotBeRetiredException;
use App\Models\Manager;
use App\Repositories\ManagerRepository;
use App\Strategies\Retirement\ManagerRetirementStrategy;
use Tests\TestCase;

/**
 * @group managers
 * @group strategies
 */
class ManagerRetirementStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function a_retirable_manager_can_be_retired_without_a_date_passed_in()
    {
        $retirementDate = null;
        $managerMock = $this->mock(Manager::class);
        $repositoryMock = $this->mock(ManagerRepository::class);
        $strategy = new ManagerRetirementStrategy($repositoryMock);

        $managerMock->expects()->canBeRetired()->once()->andReturns(true);
        $repositoryMock->expects()->retire($managerMock, $retirementDate)->once()->andReturns($managerMock);

        $strategy->setRetirable($managerMock)->retire($retirementDate);
    }

    /**
     * @test
     */
    public function a_retirable_manager_can_be_retired_with_a_given_date()
    {
        $retirementDate = now()->toDateTimeString();
        $managerMock = $this->mock(Manager::class);
        $repositoryMock = $this->mock(ManagerRepository::class);
        $strategy = new ManagerRetirementStrategy($repositoryMock);

        $managerMock->expects()->canBeRetired()->andReturns(true);
        $repositoryMock->expects()->retire($managerMock, $retirementDate)->once()->andReturns();

        $strategy->setRetirable($managerMock)->retire($retirementDate);
    }

    /**
     * @test
     */
    public function a_retirable_manager_that_cannot_be_retired_throws_an_exception()
    {
        $retirementDate = null;
        $managerMock = $this->mock(Manager::class);
        $repositoryMock = $this->mock(ManagerRepository::class);
        $strategy = new ManagerRetirementStrategy($repositoryMock);

        $managerMock->expects()->canBeRetired()->andReturns(false);
        $repositoryMock->shouldNotReceive('retire');

        $this->expectException(CannotBeRetiredException::class);

        $strategy->setRetirable($managerMock)->retire($retirementDate);
    }
}
