<?php

namespace Tests\Unit\Strategies\Release;

use App\Exceptions\CannotBeReleasedException;
use App\Models\Manager;
use App\Repositories\ManagerRepository;
use App\Strategies\Release\ManagerReleaseStrategy;
use Tests\TestCase;

/**
 * @group managers
 * @group strategies
 */
class ManagerReleaseStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function a_releasable_manager_can_be_released_without_a_date_passed_in()
    {
        $releaseDate = null;
        $managerMock = $this->mock(Manager::class);
        $repositoryMock = $this->mock(ManagerRepository::class);
        $strategy = new ManagerReleaseStrategy($repositoryMock);

        $managerMock->expects()->canBeReleased()->once()->andReturns(true);
        $repositoryMock->expects()->release($managerMock, $releaseDate)->once()->andReturns($managerMock);

        $strategy->setReleasable($managerMock)->release($releaseDate);
    }

    /**
     * @test
     */
    public function a_releasable_manager_can_be_released_with_a_given_date()
    {
        $releaseDate = now()->toDateTimeString();
        $managerMock = $this->mock(Manager::class);
        $repositoryMock = $this->mock(ManagerRepository::class);
        $strategy = new ManagerReleaseStrategy($repositoryMock);

        $managerMock->expects()->canBeReleased()->andReturns(true);
        $repositoryMock->expects()->release($managerMock, $releaseDate)->once()->andReturns();

        $strategy->setReleasable($managerMock)->release($releaseDate);
    }

    /**
     * @test
     */
    public function a_releasable_manager_that_cannot_be_released_throws_an_exception()
    {
        $releaseDate = null;
        $managerMock = $this->mock(Manager::class);
        $repositoryMock = $this->mock(ManagerRepository::class);
        $strategy = new ManagerReleaseStrategy($repositoryMock);

        $managerMock->expects()->canBeReleased()->andReturns(false);
        $repositoryMock->shouldNotReceive('release');

        $this->expectException(CannotBeReleasedException::class);

        $strategy->setReleasable($managerMock)->release($releaseDate);
    }
}
