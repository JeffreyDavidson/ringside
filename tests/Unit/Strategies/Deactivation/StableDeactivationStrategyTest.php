<?php

namespace Tests\Unit\Strategies\Deactivation;

use App\Exceptions\CannotBeDeactivatedException;
use App\Models\Stable;
use App\Repositories\StableRepository;
use App\Strategies\Deactivation\StableDeactivationStrategy;
use Tests\TestCase;

/**
 * @group stables
 * @group strategies
 */
class StableDeactivationStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function a_deactivatable_stable_can_be_deactivated_without_a_date_passed_in()
    {
        $deactivationDate = null;
        $stableMock = $this->mock(Stable::class);
        $repositoryMock = $this->mock(StableRepository::class);
        $strategy = new StableDeactivationStrategy($repositoryMock);

        $stableMock->expects()->canBeDeactivated()->once()->andReturns(true);
        $repositoryMock->expects()->deactivate($stableMock, $deactivationDate)->once()->andReturns($stableMock);

        $strategy->setDeactivatable($stableMock)->deactivate($deactivationDate);
    }

    /**
     * @test
     */
    public function a_deactivatable_stable_can_be_deactivated_with_a_given_date()
    {
        $deactivationDate = now()->toDateTimeString();
        $stableMock = $this->mock(Stable::class);
        $repositoryMock = $this->mock(StableRepository::class);
        $strategy = new StableDeactivationStrategy($repositoryMock);

        $stableMock->expects()->canBeDeactivated()->andReturns(true);
        $repositoryMock->expects()->deactivate($stableMock, $deactivationDate)->once()->andReturns();

        $strategy->setDeactivatable($stableMock)->deactivate($deactivationDate);
    }

    /**
     * @test
     */
    public function a_deactivatable_stable_that_cannot_be_deactivated_throws_an_exception()
    {
        $deactivationDate = null;
        $stableMock = $this->mock(Stable::class);
        $repositoryMock = $this->mock(StableRepository::class);
        $strategy = new StableDeactivationStrategy($repositoryMock);

        $stableMock->expects()->canBeDeactivated()->andReturns(false);
        $repositoryMock->shouldNotReceive('deactivate');

        $this->expectException(CannotBeDeactivatedException::class);

        $strategy->setDeactivatable($stableMock)->deactivate($deactivationDate);
    }
}
