<?php

namespace Tests\Unit\Strategies\Suspension;

use App\Exceptions\CannotBeSuspendedException;
use App\Models\Wrestler;
use App\Repositories\WrestlerRepository;
use App\Strategies\Suspension\WrestlerSuspensionStrategy;
use Tests\TestCase;

/**
 * @group wrestlers
 * @group strategies
 */
class WrestlerSuspensionStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function a_suspendable_wrestler_can_be_suspended_without_a_date_passed_in()
    {
        $suspensionDate = null;
        $wrestlerMock = $this->mock(Wrestler::class);
        $repositoryMock = $this->mock(WrestlerRepository::class);
        $strategy = new WrestlerSuspensionStrategy($repositoryMock);

        $wrestlerMock->expects()->canBeSuspended()->once()->andReturns(true);
        $repositoryMock->expects()->suspend($wrestlerMock, $suspensionDate)->once()->andReturns($wrestlerMock);

        $strategy->setSuspendable($wrestlerMock)->suspend($suspensionDate);
    }

    /**
     * @test
     */
    public function a_suspendable_wrestler_can_be_suspended_with_a_given_date()
    {
        $suspensionDate = now()->toDateTimeString();
        $wrestlerMock = $this->mock(Wrestler::class);
        $repositoryMock = $this->mock(WrestlerRepository::class);
        $strategy = new WrestlerSuspensionStrategy($repositoryMock);

        $wrestlerMock->expects()->canBeSuspended()->andReturns(true);
        $repositoryMock->expects()->suspend($wrestlerMock, $suspensionDate)->once()->andReturns();

        $strategy->setSuspendable($wrestlerMock)->suspend($suspensionDate);
    }

    /**
     * @test
     */
    public function a_suspendable_wrestler_that_cannot_be_suspended_throws_an_exception()
    {
        $suspensionDate = null;
        $wrestlerMock = $this->mock(Wrestler::class);
        $repositoryMock = $this->mock(WrestlerRepository::class);
        $strategy = new WrestlerSuspensionStrategy($repositoryMock);

        $wrestlerMock->expects()->canBeSuspended()->andReturns(false);
        $repositoryMock->shouldNotReceive('suspend');

        $this->expectException(CannotBeSuspendedException::class);

        $strategy->setSuspendable($wrestlerMock)->suspend($suspensionDate);
    }
}
