<?php

namespace Tests\Unit\Strategies\ClearInjury;

use App\Exceptions\CannotBeClearedFromInjuryException;
use App\Models\Wrestler;
use App\Repositories\WrestlerRepository;
use App\Strategies\ClearInjury\WrestlerClearInjuryStrategy;
use Tests\TestCase;

/**
 * @group wrestlers
 * @group strategies
 */
class WrestlerClearInjuryStrategyTest extends TestCase
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
    public function an_injured_wrestler_can_be_cleared_from_an_injury_with_a_date()
    {
        $recoveryDate = now()->toDateTimeString();
        $wrestlerMock = $this->mock(Wrestler::class);
        $repositoryMock = $this->mock(WrestlerRepository::class);
        $strategy = new WrestlerClearInjuryStrategy($repositoryMock);

        $wrestlerMock->expects()->canBeClearedFromInjury()->andReturns(true);
        $repositoryMock->expects()->clearInjury($wrestlerMock, $recoveryDate)->once()->andReturns();

        $strategy->setInjurable($wrestlerMock)->clearInjury($recoveryDate);
    }

    /**
     * @test
     */
    public function an_uninjurable_wrestler_throws_an_exception()
    {
        $recoveryDate = null;
        $wrestlerMock = $this->mock(Wrestler::class);
        $repositoryMock = $this->mock(WrestlerRepository::class);
        $strategy = new WrestlerClearInjuryStrategy($repositoryMock);

        $wrestlerMock->expects()->canBeClearedFromInjury()->andReturns(false);
        $repositoryMock->shouldNotReceive('clearInjury');

        $this->expectException(CannotBeClearedFromInjuryException::class);

        $strategy->setInjurable($wrestlerMock)->clearInjury($recoveryDate);
    }
}
