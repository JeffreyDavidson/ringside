<?php

namespace Tests\Unit\Strategies\ClearInjury;

use App\Exceptions\CannotBeClearedFromInjuryException;
use App\Models\Referee;
use App\Repositories\RefereeRepository;
use App\Strategies\ClearInjury\RefereeClearInjuryStrategy;
use Tests\TestCase;

/**
 * @group referees
 * @group strategies
 */
class RefereeClearInjuryStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function an_injured_referee_can_be_cleared_from_an_injury_without_a_date_passed_in()
    {
        $recoveryDate = null;
        $refereeMock = $this->mock(Referee::class);
        $repositoryMock = $this->mock(RefereeRepository::class);
        $strategy = new RefereeClearInjuryStrategy($repositoryMock);

        $refereeMock->expects()->canBeClearedFromInjury()->once()->andReturns(true);
        $repositoryMock->expects()->clearInjury($refereeMock, $recoveryDate)->once()->andReturns($refereeMock);

        $strategy->setInjurable($refereeMock)->clearInjury($recoveryDate);
    }

    /**
     * @test
     */
    public function an_injured_referee_can_be_cleared_from_an_injury_with_a_date()
    {
        $recoveryDate = now()->toDateTimeString();
        $refereeMock = $this->mock(Referee::class);
        $repositoryMock = $this->mock(RefereeRepository::class);
        $strategy = new RefereeClearInjuryStrategy($repositoryMock);

        $refereeMock->expects()->canBeClearedFromInjury()->andReturns(true);
        $repositoryMock->expects()->clearInjury($refereeMock, $recoveryDate)->once()->andReturns();

        $strategy->setInjurable($refereeMock)->clearInjury($recoveryDate);
    }

    /**
     * @test
     */
    public function an_uninjurable_referee_throws_an_exception()
    {
        $recoveryDate = null;
        $refereeMock = $this->mock(Referee::class);
        $repositoryMock = $this->mock(RefereeRepository::class);
        $strategy = new RefereeClearInjuryStrategy($repositoryMock);

        $refereeMock->expects()->canBeClearedFromInjury()->andReturns(false);
        $repositoryMock->shouldNotReceive('clearInjury');

        $this->expectException(CannotBeClearedFromInjuryException::class);

        $strategy->setInjurable($refereeMock)->clearInjury($recoveryDate);
    }
}
