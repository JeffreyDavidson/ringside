<?php

namespace Tests\Unit\Strategies\Retirement;

use App\Exceptions\CannotBeRetiredException;
use App\Models\Wrestler;
use App\Repositories\WrestlerRepository;
use App\Strategies\Retirement\WrestlerRetirementStrategy;
use Tests\TestCase;

/**
 * @group wrestlers
 * @group strategies
 */
class WrestlerRetirementStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function a_retirable_wrestler_can_be_retired_without_a_date_passed_in()
    {
        $retirementDate = null;
        $wrestlerMock = $this->mock(Wrestler::class);
        $repositoryMock = $this->mock(WrestlerRepository::class);
        $strategy = new WrestlerRetirementStrategy($repositoryMock);

        $wrestlerMock->expects()->canBeRetired()->once()->andReturns(true);
        $repositoryMock->expects()->retire($wrestlerMock, $retirementDate)->once()->andReturns($wrestlerMock);

        $strategy->setRetirable($wrestlerMock)->retire($retirementDate);
    }

    /**
     * @test
     */
    public function a_retirable_wrestler_can_be_retired_with_a_given_date()
    {
        $retirementDate = now()->toDateTimeString();
        $wrestlerMock = $this->mock(Wrestler::class);
        $repositoryMock = $this->mock(WrestlerRepository::class);
        $strategy = new WrestlerRetirementStrategy($repositoryMock);

        $wrestlerMock->expects()->canBeRetired()->andReturns(true);
        $repositoryMock->expects()->retire($wrestlerMock, $retirementDate)->once()->andReturns();

        $strategy->setRetirable($wrestlerMock)->retire($retirementDate);
    }

    /**
     * @test
     */
    public function a_retirable_wrestler_that_cannot_be_retired_throws_an_exception()
    {
        $retirementDate = null;
        $wrestlerMock = $this->mock(Wrestler::class);
        $repositoryMock = $this->mock(WrestlerRepository::class);
        $strategy = new WrestlerRetirementStrategy($repositoryMock);

        $wrestlerMock->expects()->canBeRetired()->andReturns(false);
        $repositoryMock->shouldNotReceive('retire');

        $this->expectException(CannotBeRetiredException::class);

        $strategy->setRetirable($wrestlerMock)->retire($retirementDate);
    }
}
