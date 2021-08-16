<?php

namespace Tests\Unit\Strategies\Suspension;

use App\Exceptions\CannotBeSuspendedException;
use App\Models\Referee;
use App\Repositories\RefereeRepository;
use App\Strategies\Suspension\RefereeSuspensionStrategy;
use Tests\TestCase;

/**
 * @group referees
 * @group strategies
 */
class RefereeSuspensionStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function a_suspendable_referee_can_be_suspended_without_a_date_passed_in()
    {
        $suspensionDate = null;
        $refereeMock = $this->mock(Referee::class);
        $repositoryMock = $this->mock(RefereeRepository::class);
        $strategy = new RefereeSuspensionStrategy($repositoryMock);

        $refereeMock->expects()->canBeSuspended()->once()->andReturns(true);
        $repositoryMock->expects()->suspend($refereeMock, $suspensionDate)->once()->andReturns($refereeMock);

        $strategy->setSuspendable($refereeMock)->suspend($suspensionDate);
    }

    /**
     * @test
     */
    public function a_suspendable_referee_can_be_suspended_with_a_given_date()
    {
        $suspensionDate = now()->toDateTimeString();
        $refereeMock = $this->mock(Referee::class);
        $repositoryMock = $this->mock(RefereeRepository::class);
        $strategy = new RefereeSuspensionStrategy($repositoryMock);

        $refereeMock->expects()->canBeSuspended()->andReturns(true);
        $repositoryMock->expects()->suspend($refereeMock, $suspensionDate)->once()->andReturns();

        $strategy->setSuspendable($refereeMock)->suspend($suspensionDate);
    }

    /**
     * @test
     */
    public function a_suspendable_referee_that_cannot_be_suspended_throws_an_exception()
    {
        $suspensionDate = null;
        $refereeMock = $this->mock(Referee::class);
        $repositoryMock = $this->mock(RefereeRepository::class);
        $strategy = new RefereeSuspensionStrategy($repositoryMock);

        $refereeMock->expects()->canBeSuspended()->andReturns(false);
        $repositoryMock->shouldNotReceive('suspend');

        $this->expectException(CannotBeSuspendedException::class);

        $strategy->setSuspendable($refereeMock)->suspend($suspensionDate);
    }
}
