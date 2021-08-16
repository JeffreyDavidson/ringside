<?php

namespace Tests\Unit\Strategies\Unretire;

use App\Exceptions\CannotBeUnretiredException;
use App\Models\Stable;
use App\Repositories\StableRepository;
use App\Strategies\Unretire\StableUnretireStrategy;
use Tests\TestCase;

/**
 * @group stables
 * @group strategies
 */
class StableUnretireStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function an_unretirable_stable_can_be_unretired_without_a_date_passed_in()
    {
        $unretireDate = null;
        $stableMock = $this->mock(Stable::class);
        $repositoryMock = $this->mock(StableRepository::class);
        $strategy = new StableUnretireStrategy($repositoryMock);

        $stableMock->expects()->canBeUnretired()->once()->andReturns(true);
        $repositoryMock->expects()->unretire($stableMock, $unretireDate)->once()->andReturns($stableMock);

        $strategy->setUnretirable($stableMock)->unretire($unretireDate);
    }

    /**
     * @test
     */
    public function an_unretirable_stable_can_be_unretired_with_a_given_date()
    {
        $unretireDate = now()->toDateTimeString();
        $stableMock = $this->mock(Stable::class);
        $repositoryMock = $this->mock(StableRepository::class);
        $strategy = new StableUnretireStrategy($repositoryMock);

        $stableMock->expects()->canBeUnretired()->andReturns(true);
        $repositoryMock->expects()->unretire($stableMock, $unretireDate)->once()->andReturns();

        $strategy->setUnretirable($stableMock)->unretire($unretireDate);
    }

    /**
     * @test
     */
    public function an_unretirable_stable_that_cannot_be_unretired_throws_an_exception()
    {
        $unretireDate = null;
        $stableMock = $this->mock(Stable::class);
        $repositoryMock = $this->mock(StableRepository::class);
        $strategy = new StableUnretireStrategy($repositoryMock);

        $stableMock->expects()->canBeUnretired()->andReturns(false);
        $repositoryMock->shouldNotReceive('unretire');

        $this->expectException(CannotBeUnretiredException::class);

        $strategy->setUnretirable($stableMock)->unretire($unretireDate);
    }
}
