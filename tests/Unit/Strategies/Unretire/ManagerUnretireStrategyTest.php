<?php

namespace Tests\Unit\Strategies\Unretire;

use App\Exceptions\CannotBeUnretiredException;
use App\Models\Manager;
use App\Repositories\ManagerRepository;
use App\Strategies\Unretire\ManagerUnretireStrategy;
use Tests\TestCase;

/**
 * @group managers
 * @group strategies
 */
class ManagerUnretireStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function an_unretirable_manager_can_be_unretired_without_a_date_passed_in()
    {
        $unretireDate = null;
        $managerMock = $this->mock(Manager::class);
        $repositoryMock = $this->mock(ManagerRepository::class);
        $strategy = new ManagerUnretireStrategy($repositoryMock);

        $managerMock->expects()->canBeUnretired()->once()->andReturns(true);
        $repositoryMock->expects()->unretire($managerMock, $unretireDate)->once()->andReturns($managerMock);

        $strategy->setUnretirable($managerMock)->unretire($unretireDate);
    }

    /**
     * @test
     */
    public function an_unretirable_manager_can_be_unretired_with_a_given_date()
    {
        $unretireDate = now()->toDateTimeString();
        $managerMock = $this->mock(Manager::class);
        $repositoryMock = $this->mock(ManagerRepository::class);
        $strategy = new ManagerUnretireStrategy($repositoryMock);

        $managerMock->expects()->canBeUnretired()->andReturns(true);
        $repositoryMock->expects()->unretire($managerMock, $unretireDate)->once()->andReturns();

        $strategy->setUnretirable($managerMock)->unretire($unretireDate);
    }

    /**
     * @test
     */
    public function an_unretirable_manager_that_cannot_be_unretired_throws_an_exception()
    {
        $unretireDate = null;
        $managerMock = $this->mock(Manager::class);
        $repositoryMock = $this->mock(ManagerRepository::class);
        $strategy = new ManagerUnretireStrategy($repositoryMock);

        $managerMock->expects()->canBeUnretired()->andReturns(false);
        $repositoryMock->shouldNotReceive('unretire');

        $this->expectException(CannotBeUnretiredException::class);

        $strategy->setUnretirable($managerMock)->unretire($unretireDate);
    }
}
