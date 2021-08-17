<?php

namespace Tests\Unit\Http\Controllers\Stables;

use App\Exceptions\CannotBeDisbandedException;
use App\Http\Controllers\Stables\DisbandController;
use App\Http\Requests\Stables\DisbandRequest;
use App\Models\Stable;
use App\Repositories\StableRepository;
use Tests\TestCase;

/**
 * @group stables
 * @group controllers
 */
class DisbandControllerTest extends TestCase
{
    /**
     * @test
     */
    public function a_disbandable_stable_can_be_disbanded_with_a_given_date()
    {
        $stableMock = $this->mock(Stable::class);
        $repositoryMock = $this->mock(StableRepository::class);
        $controller = new DisbandController;

        $stableMock->expects()->canBeDisbanded()->andReturns(true);
        $repositoryMock->expects()->disband($stableMock, now()->toDateTimeString())->once()->andReturns();
        $stableMock->expects()->updateStatusAndSave()->once();

        $controller->__invoke($stableMock, new DisbandRequest, $repositoryMock);
    }

    /**
     * @test
     */
    public function a_disbanded_stable_that_cannot_be_disbanded_throws_an_exception()
    {
        $stableMock = $this->mock(Stable::class);
        $repositoryMock = $this->mock(StableRepository::class);
        $controller = new DisbandController;

        $stableMock->expects()->canBeDisbanded()->andReturns(false);
        $repositoryMock->shouldNotReceive('disband');

        $this->expectException(CannotBeDisbandedException::class);

        $controller->__invoke($stableMock, new DisbandRequest, $repositoryMock);
    }
}
