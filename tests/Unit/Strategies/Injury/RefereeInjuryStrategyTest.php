<?php

namespace Tests\Unit\Strategies\Injury;

use App\Models\Referee;
use App\Repositories\RefereeRepository;
use App\Strategies\Injury\RefereeInjuryStrategy;
use Tests\TestCase;

/**
 * @group referees
 * @group strategies
 */
class RefereeInjuryStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function an_injurable_referee_can_injured_without_a_date_passed_in()
    {
        $injureDate = null;
        $refereeMock = $this->mock(Referee::class);
        $repositoryMock = $this->mock(RefereeRepository::class);
        $strategy = new RefereeInjuryStrategy($repositoryMock);

        $refereeMock->expects()->canBeInjured()->once()->andReturns(true);
        $repositoryMock->expects()->injure($refereeMock, $injureDate)->once()->andReturns($refereeMock);

        $strategy->setInjurable($refereeMock)->injury($injureDate);
    }

    /**
     * @test
     */
    public function an_injurable_referee_can_be_injured_with_a_given_date()
    {
        $injureDate = now()->toDateTimeString();
        $refereeMock = $this->mock(Referee::class);
        $repositoryMock = $this->mock(RefereeRepository::class);
        $strategy = new RefereeInjuryStrategy($repositoryMock);

        $refereeMock->expects()->canBeInjured()->andReturns(true);
        $repositoryMock->expects()->injure($refereeMock, $injureDate)->once()->andReturns();

        $strategy->setInjurable($refereeMock)->injure($injureDate);
    }

    /**
     * @test
     */
    public function a_referee_that_cannot_be_injured_throws_an_exception()
    {
        $injureDate = null;
        $refereeMock = $this->mock(Referee::class);
        $repositoryMock = $this->mock(RefereeRepository::class);
        $strategy = new RefereeInjuryStrategy($repositoryMock);

        $refereeMock->expects()->canBeInjured()->andReturns(false);
        $repositoryMock->shouldNotReceive('injured');

        $this->expectException(CannotBeInjuredException::class);

        $strategy->setInjurable($refereeMock)->injure($injureDate);
    }
}
