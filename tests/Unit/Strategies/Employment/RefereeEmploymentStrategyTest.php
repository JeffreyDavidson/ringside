<?php

namespace Tests\Unit\Strategies\Employment;

use App\Exceptions\CannotBeEmployedException;
use App\Models\Referee;
use App\Repositories\RefereeRepository;
use App\Strategies\Employment\RefereeEmploymentStrategy;
use Tests\TestCase;

/**
 * @group referees
 * @group strategies
 */
class RefereeEmploymentStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function an_employable_referee_can_be_employed_without_a_date_passed_in()
    {
        $employmentDate = null;
        $refereeMock = $this->mock(Referee::class);
        $repositoryMock = $this->mock(RefereeRepository::class);
        $strategy = new RefereeEmploymentStrategy($repositoryMock);

        $refereeMock->expects()->canBeEmployed()->once()->andReturns(true);
        $repositoryMock->expects()->employ($refereeMock, $employmentDate)->once()->andReturns($refereeMock);

        $strategy->setEmployable($refereeMock)->employ($employmentDate);
    }

    /**
     * @test
     */
    public function an_employable_referee_can_be_employed_with_a_given_date()
    {
        $employmentDate = now()->toDateTimeString();
        $refereeMock = $this->mock(Referee::class);
        $repositoryMock = $this->mock(RefereeRepository::class);
        $strategy = new RefereeEmploymentStrategy($repositoryMock);

        $refereeMock->expects()->canBeEmployed()->andReturns(true);
        $repositoryMock->expects()->employ($refereeMock, $employmentDate)->once()->andReturns();

        $strategy->setEmployable($refereeMock)->employ($employmentDate);
    }

    /**
     * @test
     */
    public function an_employable_referee_that_cannot_be_employed_throws_an_exception()
    {
        $employmentDate = null;
        $refereeMock = $this->mock(Referee::class);
        $repositoryMock = $this->mock(RefereeRepository::class);
        $strategy = new RefereeEmploymentStrategy($repositoryMock);

        $refereeMock->expects()->canBeEmployed()->andReturns(false);
        $repositoryMock->shouldNotReceive('employ');

        $this->expectException(CannotBeEmployedException::class);

        $strategy->setEmployable($refereeMock)->employ($employmentDate);
    }
}
