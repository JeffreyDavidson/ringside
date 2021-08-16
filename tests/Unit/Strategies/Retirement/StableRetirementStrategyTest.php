<?php

namespace Tests\Unit\Strategies\Retirement;

use App\Exceptions\CannotBeRetiredException;
use App\Models\Stable;
use App\Repositories\StableRepository;
use App\Strategies\Retirement\StableRetirementStrategy;
use Tests\TestCase;

/**
 * @group stables
 * @group strategies
 */
class StableRetirementStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function a_retirable_stable_can_be_retired_without_a_date_passed_in()
    {
        $retirementDate = null;
        $stableMock = $this->mock(Stable::class);
        $repositoryMock = $this->mock(StableRepository::class);
        $strategy = new StableRetirementStrategy($repositoryMock);

        $stableMock->expects()->canBeRetired()->once()->andReturns(true);
        $repositoryMock->expects()->retire($stableMock, $retirementDate)->once()->andReturns($stableMock);

        $strategy->setRetirable($stableMock)->retire($retirementDate);
    }

    /**
     * @test
     */
    public function a_retirable_stable_can_be_retired_with_a_given_date()
    {
        $retirementDate = now()->toDateTimeString();
        $stableMock = $this->mock(Stable::class);
        $repositoryMock = $this->mock(StableRepository::class);
        $strategy = new StableRetirementStrategy($repositoryMock);

        $stableMock->expects()->canBeRetired()->andReturns(true);
        $repositoryMock->expects()->retire($stableMock, $retirementDate)->once()->andReturns();

        $strategy->setRetirable($stableMock)->retire($retirementDate);
    }

    /**
     * @test
     */
    public function a_retirable_stable_that_cannot_be_retired_throws_an_exception()
    {
        $retirementDate = null;
        $stableMock = $this->mock(Stable::class);
        $repositoryMock = $this->mock(StableRepository::class);
        $strategy = new StableRetirementStrategy($repositoryMock);

        $stableMock->expects()->canBeRetired()->andReturns(false);
        $repositoryMock->shouldNotReceive('retire');

        $this->expectException(CannotBeRetiredException::class);

        $strategy->setRetirable($stableMock)->retire($retirementDate);
    }
}
