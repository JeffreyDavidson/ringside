<?php

namespace Tests\Unit\Strategies\Injury;

use App\Exceptions\CannotBeInjuredException;
use App\Models\Manager;
use App\Repositories\ManagerRepository;
use App\Strategies\Injury\ManagerInjuryStrategy;
use Tests\TestCase;

/**
 * @group managers
 * @group strategies
 */
class ManagerInjuryStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function an_injurable_manager_can_be_injured_without_a_date_passed_in()
    {
        $injureDate = null;
        $managerMock = $this->mock(Manager::class);
        $repositoryMock = $this->mock(ManagerRepository::class);
        $strategy = new ManagerInjuryStrategy($repositoryMock);

        $managerMock->expects()->canBeInjured()->once()->andReturns(true);
        $repositoryMock->expects()->injure($managerMock, $injureDate)->once()->andReturns($managerMock);

        $strategy->setInjurable($managerMock)->injure($injureDate);
    }

    /**
     * @test
     */
    public function an_injurable_manager_can_be_injured_with_a_given_date()
    {
        $injureDate = now()->toDateTimeString();
        $managerMock = $this->mock(Manager::class);
        $repositoryMock = $this->mock(ManagerRepository::class);
        $strategy = new ManagerInjuryStrategy($repositoryMock);

        $managerMock->expects()->canBeInjured()->andReturns(true);
        $repositoryMock->expects()->injure($managerMock, $injureDate)->once()->andReturns();

        $strategy->setInjurable($managerMock)->injure($injureDate);
    }

    /**
     * @test
     */
    public function a_manager_that_cannot_be_injured_throws_an_exception()
    {
        $injureDate = null;
        $managerMock = $this->mock(Manager::class);
        $repositoryMock = $this->mock(ManagerRepository::class);
        $strategy = new ManagerInjuryStrategy($repositoryMock);

        $managerMock->expects()->canBeInjured()->andReturns(false);
        $repositoryMock->shouldNotReceive('injure');

        $this->expectException(CannotBeInjuredException::class);

        $strategy->setInjurable($managerMock)->injure($injureDate);
    }
}
