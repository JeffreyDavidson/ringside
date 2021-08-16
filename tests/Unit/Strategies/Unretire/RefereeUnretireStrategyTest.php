<?php

namespace Tests\Unit\Strategies\Unretire;

use App\Exceptions\CannotBeUnretiredException;
use App\Models\Referee;
use App\Repositories\RefereeRepository;
use App\Strategies\Unretire\RefereeUnretireStrategy;
use Tests\TestCase;

/**
 * @group referees
 * @group strategies
 */
class RefereeUnretireStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function an_unretirable_referee_can_be_unretired_without_a_date_passed_in()
    {
        $unretireDate = null;
        $refereeMock = $this->mock(Referee::class);
        $repositoryMock = $this->mock(RefereeRepository::class);
        $strategy = new RefereeUnretireStrategy($repositoryMock);

        $refereeMock->expects()->canBeUnretired()->once()->andReturns(true);
        $repositoryMock->expects()->unretire($refereeMock, $unretireDate)->once()->andReturns($refereeMock);

        $strategy->setUnretirable($refereeMock)->unretire($unretireDate);
    }

    /**
     * @test
     */
    public function an_unretirable_referee_can_be_unretired_with_a_given_date()
    {
        $unretireDate = now()->toDateTimeString();
        $refereeMock = $this->mock(Referee::class);
        $repositoryMock = $this->mock(RefereeRepository::class);
        $strategy = new RefereeUnretireStrategy($repositoryMock);

        $refereeMock->expects()->canBeUnretired()->andReturns(true);
        $repositoryMock->expects()->unretire($refereeMock, $unretireDate)->once()->andReturns();

        $strategy->setUnretirable($refereeMock)->unretire($unretireDate);
    }

    /**
     * @test
     */
    public function an_unretirable_referee_that_cannot_be_unretired_throws_an_exception()
    {
        $unretireDate = null;
        $refereeMock = $this->mock(Referee::class);
        $repositoryMock = $this->mock(RefereeRepository::class);
        $strategy = new RefereeUnretireStrategy($repositoryMock);

        $refereeMock->expects()->canBeUnretired()->andReturns(false);
        $repositoryMock->shouldNotReceive('unretire');

        $this->expectException(CannotBeUnretiredException::class);

        $strategy->setUnretirable($refereeMock)->unretire($unretireDate);
    }
}
