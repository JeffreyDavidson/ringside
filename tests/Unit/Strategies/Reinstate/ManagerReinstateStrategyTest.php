<?php

namespace Tests\Unit\Strategies\Reinstate;

use App\Exceptions\CannotBeReinstatedException;
use App\Models\Manager;
use App\Repositories\ManagerRepository;
use App\Strategies\Reinstate\ManagerReinstateStrategy;
use Tests\TestCase;

/**
 * @group managers
 * @group strategies
 */
class ManagerReinstateStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function a_reinstatable_manager_can_be_reinstated_without_a_date_passed_in()
    {
        $reinstatementDate = null;
        $managerMock = $this->mock(Manager::class);
        $repositoryMock = $this->mock(ManagerRepository::class);
        $strategy = new ManagerReinstateStrategy($repositoryMock);

        $managerMock->expects()->canBeReinstated()->once()->andReturns(true);
        $repositoryMock->expects()->reinstate($managerMock, $reinstatementDate)->once()->andReturns($managerMock);

        $strategy->setReinstatable($managerMock)->reinstate($reinstatementDate);
    }

    /**
     * @test
     */
    public function a_reinstatable_manager_can_be_reinstated_with_a_given_date()
    {
        $reinstatementDate = now()->toDateTimeString();
        $managerMock = $this->mock(Manager::class);
        $repositoryMock = $this->mock(ManagerRepository::class);
        $strategy = new ManagerReinstateStrategy($repositoryMock);

        $managerMock->expects()->canBeReinstated()->andReturns(true);
        $repositoryMock->expects()->release($managerMock, $reinstatementDate)->once()->andReturns();

        $strategy->setReinstatable($managerMock)->reinstate($reinstatementDate);
    }

    /**
     * @test
     */
    public function a_reinstatable_manager_that_cannot_be_reinstated_throws_an_exception()
    {
        $reinstatementDate = null;
        $managerMock = $this->mock(Manager::class);
        $repositoryMock = $this->mock(ManagerRepository::class);
        $strategy = new ManagerReinstateStrategy($repositoryMock);

        $managerMock->expects()->canBeReinstated()->andReturns(false);
        $repositoryMock->shouldNotReceive('reinstate');

        $this->expectException(CannotBeReinstatedException::class);

        $strategy->setReinstatable($managerMock)->reinstate($reinstatementDate);
    }
}
