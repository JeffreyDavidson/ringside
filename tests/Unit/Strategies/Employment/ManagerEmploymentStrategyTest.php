<?php

namespace Tests\Unit\Strategies\Employment;

use App\Exceptions\CannotBeEmployedException;
use App\Models\Manager;
use App\Repositories\ManagerRepository;
use App\Strategies\Employment\ManagerEmploymentStrategy;
use Tests\TestCase;

/**
 * @group managers
 * @group strategies
 */
class ManagerEmploymentStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function an_employable_manager_can_be_employed_without_a_date_passed_in()
    {
        $employmentDate = null;
        $managerMock = $this->mock(Manager::class);
        $repositoryMock = $this->mock(ManagerRepository::class);
        $strategy = new ManagerEmploymentStrategy($repositoryMock);

        $managerMock->expects()->canBeEmployed()->once()->andReturns(true);
        $repositoryMock->expects()->employ($managerMock, $employmentDate)->once()->andReturns($managerMock);

        $strategy->setEmployable($managerMock)->employ($employmentDate);
    }

    /**
     * @test
     */
    public function an_employable_manager_can_be_employed_with_a_given_date()
    {
        $employmentDate = now()->toDateTimeString();
        $managerMock = $this->mock(Manager::class);
        $repositoryMock = $this->mock(ManagerRepository::class);
        $strategy = new ManagerEmploymentStrategy($repositoryMock);

        $managerMock->expects()->canBeEmployed()->andReturns(true);
        $repositoryMock->expects()->employ($managerMock, $employmentDate)->once()->andReturns();

        $strategy->setEmployable($managerMock)->employ($employmentDate);
    }

    /**
     * @test
     */
    public function an_employable_manager_that_cannot_be_employed_throws_an_exception()
    {
        $employmentDate = null;
        $managerMock = $this->mock(Manager::class);
        $repositoryMock = $this->mock(ManagerRepository::class);
        $strategy = new ManagerEmploymentStrategy($repositoryMock);

        $managerMock->expects()->canBeEmployed()->andReturns(false);
        $repositoryMock->shouldNotReceive('employ');

        $this->expectException(CannotBeEmployedException::class);

        $strategy->setEmployable($managerMock)->employ($employmentDate);
    }
}
