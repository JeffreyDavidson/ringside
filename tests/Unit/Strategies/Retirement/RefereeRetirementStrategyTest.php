<?php

namespace Tests\Unit\Strategies\Retirement;

use App\Exceptions\CannotBeRetiredException;
use App\Models\Referee;
use App\Repositories\RefereeRepository;
use App\Strategies\Retirement\RefereeRetirementStrategy;
use Tests\TestCase;

/**
 * @group referees
 * @group strategies
 */
class RefereeRetirementStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function a_retirable_referee_can_be_retired_without_a_date_passed_in()
    {
        $retirementDate = null;
        $refereeMock = $this->mock(Referee::class);
        $repositoryMock = $this->mock(RefereeRepository::class);
        $strategy = new RefereeRetirementStrategy($repositoryMock);

        $refereeMock->expects()->canBeRetired()->once()->andReturns(true);
        $repositoryMock->expects()->retire($refereeMock, $retirementDate)->once()->andReturns($refereeMock);

        $strategy->setRetirable($refereeMock)->retire($retirementDate);
    }

    /**
     * @test
     */
    public function a_retirable_referee_can_be_retired_with_a_given_date()
    {
        $retirementDate = now()->toDateTimeString();
        $refereeMock = $this->mock(Referee::class);
        $repositoryMock = $this->mock(RefereeRepository::class);
        $strategy = new RefereeRetirementStrategy($repositoryMock);

        $refereeMock->expects()->canBeRetired()->andReturns(true);
        $repositoryMock->expects()->retire($refereeMock, $retirementDate)->once()->andReturns();

        $strategy->setRetirable($refereeMock)->retire($retirementDate);
    }

    /**
     * @test
     */
    public function a_retirable_referee_that_cannot_be_retired_throws_an_exception()
    {
        $retirementDate = null;
        $refereeMock = $this->mock(Referee::class);
        $repositoryMock = $this->mock(RefereeRepository::class);
        $strategy = new RefereeRetirementStrategy($repositoryMock);

        $refereeMock->expects()->canBeRetired()->andReturns(false);
        $repositoryMock->shouldNotReceive('retire');

        $this->expectException(CannotBeRetiredException::class);

        $strategy->setRetirable($refereeMock)->retire($retirementDate);
    }
}
