<?php

namespace Tests\Unit\Strategies\Unretire;

use App\Exceptions\CannotBeUnretiredException;
use App\Models\Wrestler;
use App\Repositories\WrestlerRepository;
use App\Strategies\Unretire\WrestlerUnretireStrategy;
use Tests\TestCase;

/**
 * @group wrestlers
 * @group strategies
 */
class WrestlerUnretireStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function an_unretirable_wrestler_can_be_unretired_without_a_date_passed_in()
    {
        $unretireDate = null;
        $wrestlerMock = $this->mock(Wrestler::class);
        $repositoryMock = $this->mock(WrestlerRepository::class);
        $strategy = new WrestlerUnretireStrategy($repositoryMock);

        $wrestlerMock->expects()->canBeUnretired()->once()->andReturns(true);
        $repositoryMock->expects()->unretire($wrestlerMock, $unretireDate)->once()->andReturns($wrestlerMock);

        $strategy->setUnretirable($wrestlerMock)->unretire($unretireDate);
    }

    /**
     * @test
     */
    public function an_unretirable_wrestler_can_be_unretired_with_a_given_date()
    {
        $unretireDate = now()->toDateTimeString();
        $wrestlerMock = $this->mock(Wrestler::class);
        $repositoryMock = $this->mock(WrestlerRepository::class);
        $strategy = new WrestlerUnretireStrategy($repositoryMock);

        $wrestlerMock->expects()->canBeUnretired()->andReturns(true);
        $repositoryMock->expects()->unretire($wrestlerMock, $unretireDate)->once()->andReturns();

        $strategy->setUnretirable($wrestlerMock)->unretire($unretireDate);
    }

    /**
     * @test
     */
    public function an_unretirable_wrestler_that_cannot_be_unretired_throws_an_exception()
    {
        $unretireDate = null;
        $wrestlerMock = $this->mock(Wrestler::class);
        $repositoryMock = $this->mock(WrestlerRepository::class);
        $strategy = new WrestlerUnretireStrategy($repositoryMock);

        $wrestlerMock->expects()->canBeUnretired()->andReturns(false);
        $repositoryMock->shouldNotReceive('unretire');

        $this->expectException(CannotBeUnretiredException::class);

        $strategy->setUnretirable($wrestlerMock)->unretire($unretireDate);
    }
}
