<?php

namespace Tests\Unit\Strategies\ClearInjury;

use App\Models\Manager;
use App\Strategies\ClearInjury\ManagerClearInjuryStrategy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManagerClearInjuryStrategyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function an_uninjurable_manager_throws_an_exception()
    {
        $strategy = new ManagerClearInjuryStrategy;
        $managerMock = $this->mock(Manager::factory()->make());
        $repositoryMock = $this->mock(ManagerRepository::class);

        $strategy->setInjurable($managerMock);
        $managerMock->expects()->canBeClearedFromInjury()->andReturns(false)->andThrow(CannotBeClearedFromInjuryException::class);
        $repositoryMock->expects()->shouldNotReceive('clearInjury');

        $strategy->clearInjury();
    }

    /**
     * @test
     */
    public function an_injured_manager_can_be_cleared_from_an_injury()
    {
        $strategy = new ManagerClearInjuryStrategy;
        $managerMock = $this->mock(Manager::factory()->make());
        $repositoryMock = $this->mock(ManagerRepository::class);

        $strategy->setInjurable($managerMock); // Needs to be actual manager object
        $managerMock->expects()->canBeClearedFromInjury()->andReturns(true);
        $repositoryMock->expects()->clearInjury($manager)->once()->andReturns($manager);

        $strategy->clearInjury();
    }
}
