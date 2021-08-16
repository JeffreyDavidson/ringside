<?php

namespace Tests\Unit\Strategies\Injury;

use App\Exceptions\CannotBeInjuredException;
use App\Models\Wrestler;
use App\Repositories\WrestlerRepository;
use App\Strategies\ClearInjury\WrestlerClearInjuryStrategy;
use App\Strategies\Injury\WrestlerInjuryStrategy;
use Tests\TestCase;

/**
 * @group wrestlers
 * @group strategies
 */
class WrestlerInjuryStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function an_injured_wrestler_can_be_cleared_from_an_injury_without_a_date_passed_in()
    {
        $recoveryDate = null;
        $wrestlerMock = $this->mock(Wrestler::class);
        $repositoryMock = $this->mock(WrestlerRepository::class);
        $strategy = new WrestlerClearInjuryStrategy($repositoryMock);

        $wrestlerMock->expects()->canBeClearedFromInjury()->once()->andReturns(true);
        $repositoryMock->expects()->clearInjury($wrestlerMock, $recoveryDate)->once()->andReturns($wrestlerMock);

        $strategy->setInjurable($wrestlerMock)->clearInjury($recoveryDate);
    }

    /**
     * @test
     */
    public function an_injured_wrestler_can_be_cleared_from_an_injury_with_a_given_date()
    {
        $recoveryDate = now()->toDateTimeString();
        $wrestlerMock = $this->mock(Wrestler::class);
        $repositoryMock = $this->mock(WrestlerRepository::class);
        $strategy = new WrestlerInjuryStrategy($repositoryMock);

        $wrestlerMock->expects()->canBeInjured()->andReturns(true);
        $repositoryMock->expects()->injure($wrestlerMock, $recoveryDate)->once()->andReturns();

        $strategy->setInjurable($wrestlerMock)->injure($recoveryDate);
    }

    /**
     * @test
     */
    public function a_wrestler_that_cannot_be_injured_throws_an_exception()
    {
        $recoveryDate = null;
        $wrestlerMock = $this->mock(Wrestler::class);
        $repositoryMock = $this->mock(WrestlerRepository::class);
        $strategy = new WrestlerInjuryStrategy($repositoryMock);

        $wrestlerMock->expects()->canBeInjured()->andReturns(false);
        $repositoryMock->shouldNotReceive('injure');

        $this->expectException(CannotBeInjuredException::class);

        $strategy->setInjurable($wrestlerMock)->injure($recoveryDate);
    }
}
