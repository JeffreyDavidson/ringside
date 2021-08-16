<?php

namespace Tests\Unit\Strategies\Employment;

use App\Exceptions\CannotBeEmployedException;
use App\Models\Wrestler;
use App\Repositories\WrestlerRepository;
use App\Strategies\Employment\WrestlerEmploymentStrategy;
use Tests\TestCase;

/**
 * @group wrestlers
 * @group strategies
 */
class WrestlerEmploymentStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function an_employable_wrestler_can_be_employed_without_a_date_passed_in()
    {
        $employmentDate = null;
        $wrestlerMock = $this->mock(Wrestler::class);
        $repositoryMock = $this->mock(WrestlerRepository::class);
        $strategy = new WrestlerEmploymentStrategy($repositoryMock);

        $wrestlerMock->expects()->canBeEmployed()->once()->andReturns(true);
        $repositoryMock->expects()->employ($wrestlerMock, $employmentDate)->once()->andReturns($wrestlerMock);

        $strategy->setEmployable($wrestlerMock)->employ($employmentDate);
    }

    /**
     * @test
     */
    public function an_employable_wrestler_can_be_employed_with_a_given_date()
    {
        $employmentDate = now()->toDateTimeString();
        $wrestlerMock = $this->mock(Wrestler::class);
        $repositoryMock = $this->mock(WrestlerRepository::class);
        $strategy = new WrestlerEmploymentStrategy($repositoryMock);

        $wrestlerMock->expects()->canBeEmployed()->andReturns(true);
        $repositoryMock->expects()->employ($wrestlerMock, $employmentDate)->once()->andReturns();

        $strategy->setEmployable($wrestlerMock)->employ($employmentDate);
    }

    /**
     * @test
     */
    public function an_employable_wrestler_that_cannot_be_employed_throws_an_exception()
    {
        $employmentDate = null;
        $wrestlerMock = $this->mock(Wrestler::class);
        $repositoryMock = $this->mock(WrestlerRepository::class);
        $strategy = new WrestlerEmploymentStrategy($repositoryMock);

        $wrestlerMock->expects()->canBeEmployed()->andReturns(false);
        $repositoryMock->shouldNotReceive('employ');

        $this->expectException(CannotBeEmployedException::class);

        $strategy->setEmployable($wrestlerMock)->employ($employmentDate);
    }
}
