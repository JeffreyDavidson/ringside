<?php

namespace Tests\Unit\Strategies\Reinstate;

use App\Exceptions\CannotBeReinstatedException;
use App\Models\Referee;
use App\Repositories\RefereeRepository;
use App\Strategies\Reinstate\RefereeReinstateStrategy;
use Tests\TestCase;

/**
 * @group referees
 * @group strategies
 */
class RefereeReinstateStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function a_reinstatable_referee_can_be_reinstated_without_a_date_passed_in()
    {
        $reinstatementDate = null;
        $refereeMock = $this->mock(Referee::class);
        $repositoryMock = $this->mock(RefereeRepository::class);
        $strategy = new RefereeReinstateStrategy($repositoryMock);

        $refereeMock->expects()->canBeReinstated()->once()->andReturns(true);
        $repositoryMock->expects()->reinstate($refereeMock, $reinstatementDate)->once()->andReturns($refereeMock);

        $strategy->setReinstatable($refereeMock)->reinstate($reinstatementDate);
    }

    /**
     * @test
     */
    public function a_reinstatable_referee_can_be_reinstated_with_a_given_date()
    {
        $reinstatementDate = now()->toDateTimeString();
        $refereeMock = $this->mock(Referee::class);
        $repositoryMock = $this->mock(RefereeRepository::class);
        $strategy = new RefereeReinstateStrategy($repositoryMock);

        $refereeMock->expects()->canBeReinstated()->andReturns(true);
        $repositoryMock->expects()->release($refereeMock, $reinstatementDate)->once()->andReturns();

        $strategy->setReinstatable($refereeMock)->reinstate($reinstatementDate);
    }

    /**
     * @test
     */
    public function a_reinstatable_referee_that_cannot_be_reinstated_throws_an_exception()
    {
        $reinstatementDate = null;
        $refereeMock = $this->mock(Referee::class);
        $repositoryMock = $this->mock(RefereeRepository::class);
        $strategy = new RefereeReinstateStrategy($repositoryMock);

        $refereeMock->expects()->canBeReinstated()->andReturns(false);
        $repositoryMock->shouldNotReceive('reinstate');

        $this->expectException(CannotBeReinstatedException::class);

        $strategy->setReinstatable($refereeMock)->reinstate($reinstatementDate);
    }
}
