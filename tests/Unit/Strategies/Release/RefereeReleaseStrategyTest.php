<?php

namespace Tests\Unit\Strategies\Release;

use App\Exceptions\CannotBeReleasedException;
use App\Models\Referee;
use App\Repositories\RefereeRepository;
use App\Strategies\Release\RefereeReleaseStrategy;
use Tests\TestCase;

/**
 * @group referees
 * @group strategies
 */
class RefereeReleaseStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function a_releasable_referee_can_be_released_without_a_date_passed_in()
    {
        $releaseDate = null;
        $refereeMock = $this->mock(Referee::class);
        $repositoryMock = $this->mock(RefereeRepository::class);
        $strategy = new RefereeReleaseStrategy($repositoryMock);

        $refereeMock->expects()->canBeReleased()->once()->andReturns(true);
        $repositoryMock->expects()->release($refereeMock, $releaseDate)->once()->andReturns($refereeMock);

        $strategy->setReleasable($refereeMock)->release($releaseDate);
    }

    /**
     * @test
     */
    public function a_releasable_referee_can_be_released_with_a_given_date()
    {
        $releaseDate = now()->toDateTimeString();
        $refereeMock = $this->mock(Referee::class);
        $repositoryMock = $this->mock(RefereeRepository::class);
        $strategy = new RefereeReleaseStrategy($repositoryMock);

        $refereeMock->expects()->canBeReleased()->andReturns(true);
        $repositoryMock->expects()->release($refereeMock, $releaseDate)->once()->andReturns();

        $strategy->setReleasable($refereeMock)->release($releaseDate);
    }

    /**
     * @test
     */
    public function a_releasable_referee_that_cannot_be_released_throws_an_exception()
    {
        $releaseDate = null;
        $refereeMock = $this->mock(Referee::class);
        $repositoryMock = $this->mock(RefereeRepository::class);
        $strategy = new RefereeReleaseStrategy($repositoryMock);

        $refereeMock->expects()->canBeReleased()->andReturns(false);
        $repositoryMock->shouldNotReceive('release');

        $this->expectException(CannotBeReleasedException::class);

        $strategy->setReleasable($refereeMock)->release($releaseDate);
    }
}
