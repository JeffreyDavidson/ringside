<?php

namespace Tests\Unit\Strategies\ClearInjury;

use App\Exceptions\CannotBeClearedFromInjuryException;
use App\Models\Manager;
use App\Strategies\ClearInjury\ManagerClearInjuryStrategy;
use Tests\TestCase;

/**
 * @group managers
 * @group strategies
 */
class ManagerClearInjuryStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function an_uninjurable_manager_throws_an_exception()
    {
        $recoveryDate = null;
        $managerMock = $this->mock(Manager::class);
        $repositoryMock = $this->mock(ManagerRepository::class);
        $strategy = new ManagerClearInjuryStrategy($repositoryMock);

        $managerMock->expects()->canBeClearedFromInjury()->andReturns(false);
        $repositoryMock->shouldNotReceive('clearInjury');

        $this->expectException(CannotBeClearedFromInjuryException::class);

        $strategy->setInjurable($managerMock)->clearInjury($recoveryDate);
    }

    /**
     * @test
     */
    public function an_injured_manager_can_be_cleared_from_an_injury_without_a_date_passed_in()
    {
        $recoveryDate = null;
        $managerMock = $this->mock(Manager::class);
        $repositoryMock = $this->mock(ManagerRepository::class);
        $strategy = new ManagerClearInjuryStrategy($repositoryMock);

        $managerMock->expects()->canBeClearedFromInjury()->andReturns(true);
        $repositoryMock->expects()->clearInjury($managerMock, $recoveryDate)->once();

        $strategy->setInjurable($managerMock)->clearInjury($recoveryDate);
    }

    /**
     * @test
     */
    public function an_injured_manager_can_be_cleared_from_an_injury_with_a_date()
    {
        $recoveryDate = now()->toDateTimeString();
        $managerMock = $this->mock(Manager::class);
        $repositoryMock = $this->mock(ManagerRepository::class);
        $strategy = new ManagerClearInjuryStrategy($repositoryMock);

        $managerMock->expects()->canBeClearedFromInjury()->andReturns(true);
        $repositoryMock->expects()->clearInjury($managerMock, $recoveryDate)->once()->andReturns();

        $strategy->setInjurable($managerMock)->clearInjury($recoveryDate);
    }
}
