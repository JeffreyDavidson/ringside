<?php

namespace Tests\Unit\Strategies\Disband;

use App\Exceptions\CannotBeDisbandedException;
use App\Models\Stable;
use App\Repositories\StableRepository;
use App\Strategies\Disband\StableDisbandStrategy;
use Tests\TestCase;

/**
 * @group stables
 * @group strategies
 */
class StableDisbandStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function a_disbandable_stable_can_be_disbanded_without_a_date_passed_in()
    {
        $activationDate = null;
        $stableMock = $this->mock(Stable::class);
        $repositoryMock = $this->mock(StableRepository::class);
        $strategy = new StableDisbandStrategy($repositoryMock);

        $stableMock->expects()->canBeDisbanded()->once()->andReturns(true);
        $repositoryMock->expects()->disband($stableMock, $activationDate)->once()->andReturns($stableMock);

        $strategy->setDisbandable($stableMock)->disband($activationDate);
    }

    /**
     * @test
     */
    public function a_disbandable_stable_can_be_disbanded_with_a_given_date()
    {
        $activationDate = now()->toDateTimeString();
        $stableMock = $this->mock(Stable::class);
        $repositoryMock = $this->mock(StableRepository::class);
        $strategy = new StableDisbandStrategy($repositoryMock);

        $stableMock->expects()->canBeDisbanded()->andReturns(true);
        $repositoryMock->expects()->disband($stableMock, $activationDate)->once()->andReturns();

        $strategy->setDisbandable($stableMock)->disband($activationDate);
    }

    /**
     * @test
     */
    public function a_disbanded_stable_that_cannot_be_disbanded_throws_an_exception()
    {
        $activationDate = null;
        $stableMock = $this->mock(Stable::class);
        $repositoryMock = $this->mock(StableRepository::class);
        $strategy = new StableDisbandStrategy($repositoryMock);

        $stableMock->expects()->canBeDisbanded()->andReturns(false);
        $repositoryMock->shouldNotReceive('disband');

        $this->expectException(CannotBeDisbandedException::class);

        $strategy->setDisbandable($stableMock)->disband($activationDate);
    }
}
