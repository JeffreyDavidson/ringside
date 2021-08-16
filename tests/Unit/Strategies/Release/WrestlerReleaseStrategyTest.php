<?php

namespace Tests\Unit\Strategies\Release;

use App\Exceptions\CannotBeReleasedException;
use App\Models\Wrestler;
use App\Repositories\WrestlerRepository;
use App\Strategies\Release\WrestlerReleaseStrategy;
use Tests\TestCase;

/**
 * @group wrestlers
 * @group strategies
 */
class WrestlerReleaseStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function a_releasable_wrestler_can_be_released_without_a_date_passed_in()
    {
        $releaseDate = null;
        $wrestlerMock = $this->mock(Wrestler::class);
        $repositoryMock = $this->mock(WrestlerRepository::class);
        $strategy = new WrestlerReleaseStrategy($repositoryMock);

        $wrestlerMock->expects()->canBeReleased()->once()->andReturns(true);
        $repositoryMock->expects()->release($wrestlerMock, $releaseDate)->once()->andReturns($wrestlerMock);

        $strategy->setReleasable($wrestlerMock)->release($releaseDate);
    }

    /**
     * @test
     */
    public function a_releasable_wrestler_can_be_released_with_a_given_date()
    {
        $releaseDate = now()->toDateTimeString();
        $wrestlerMock = $this->mock(Wrestler::class);
        $repositoryMock = $this->mock(WrestlerRepository::class);
        $strategy = new WrestlerReleaseStrategy($repositoryMock);

        $wrestlerMock->expects()->canBeReleased()->andReturns(true);
        $repositoryMock->expects()->release($wrestlerMock, $releaseDate)->once()->andReturns();

        $strategy->setReleasable($wrestlerMock)->release($releaseDate);
    }

    /**
     * @test
     */
    public function a_releasable_wrestler_that_cannot_be_released_throws_an_exception()
    {
        $releaseDate = null;
        $wrestlerMock = $this->mock(Wrestler::class);
        $repositoryMock = $this->mock(WrestlerRepository::class);
        $strategy = new WrestlerReleaseStrategy($repositoryMock);

        $wrestlerMock->expects()->canBeReleased()->andReturns(false);
        $repositoryMock->shouldNotReceive('release');

        $this->expectException(CannotBeReleasedException::class);

        $strategy->setReleasable($wrestlerMock)->release($releaseDate);
    }
}
