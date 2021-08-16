<?php

namespace Tests\Unit\Strategies\Reinstate;

use App\Exceptions\CannotBeReinstatedException;
use App\Models\Wrestler;
use App\Repositories\WrestlerRepository;
use App\Strategies\Reinstate\WrestlerReinstateStrategy;
use Tests\TestCase;

/**
 * @group wrestlers
 * @group strategies
 */
class WrestlerReinstateStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function a_reinstatable_wrestler_can_be_reinstated_without_a_date_passed_in()
    {
        $reinstatementDate = null;
        $wrestlerMock = $this->mock(Wrestler::class);
        $repositoryMock = $this->mock(WrestlerRepository::class);
        $strategy = new WrestlerReinstateStrategy($repositoryMock);

        $wrestlerMock->expects()->canBeReinstated()->once()->andReturns(true);
        $repositoryMock->expects()->reinstate($wrestlerMock, $reinstatementDate)->once()->andReturns($wrestlerMock);

        $strategy->setReinstatable($wrestlerMock)->reinstate($reinstatementDate);
    }

    /**
     * @test
     */
    public function a_reinstatable_wrestler_can_be_reinstated_with_a_given_date()
    {
        $reinstatementDate = now()->toDateTimeString();
        $wrestlerMock = $this->mock(Wrestler::class);
        $repositoryMock = $this->mock(WrestlerRepository::class);
        $strategy = new WrestlerReinstateStrategy($repositoryMock);

        $wrestlerMock->expects()->canBeReinstated()->andReturns(true);
        $repositoryMock->expects()->release($wrestlerMock, $reinstatementDate)->once()->andReturns();

        $strategy->setReinstatable($wrestlerMock)->reinstate($reinstatementDate);
    }

    /**
     * @test
     */
    public function a_reinstatable_wrestler_that_cannot_be_reinstated_throws_an_exception()
    {
        $reinstatementDate = null;
        $wrestlerMock = $this->mock(Wrestler::class);
        $repositoryMock = $this->mock(WrestlerRepository::class);
        $strategy = new WrestlerReinstateStrategy($repositoryMock);

        $wrestlerMock->expects()->canBeReinstated()->andReturns(false);
        $repositoryMock->shouldNotReceive('reinstate');

        $this->expectException(CannotBeReinstatedException::class);

        $strategy->setReinstatable($wrestlerMock)->reinstate($reinstatementDate);
    }
}
