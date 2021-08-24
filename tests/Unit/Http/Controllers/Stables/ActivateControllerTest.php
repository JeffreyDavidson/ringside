<?php

namespace Tests\Unit\Http\Controllers\Stables;

use App\Exceptions\CannotBeActivatedException;
use App\Http\Controllers\Stables\ActivateController;
use App\Http\Requests\Stables\ActivateRequest;
use App\Models\Stable;
use App\Repositories\StableRepository;
use Tests\TestCase;

/**
 * @group stables
 * @group controllers
 */
class ActivateControllerTest extends TestCase
{
    /**
     * @test
     */
    public function an_activatable_stable_can_be_activated_with_a_given_date()
    {
        $stableMock = $this->mock(Stable::class);
        $repositoryMock = $this->mock(StableRepository::class);
        $controller = new ActivateController;

        $stableMock->expects()->canBeActivated()->andReturns(true);
        $repositoryMock->expects()->activate($stableMock, now()->toDateTimeString())->once()->andReturns();
        $stableMock->expects()->updateStatus()->save()->once();

        $controller->__invoke($stableMock, new ActivateRequest, $repositoryMock);
    }

    /**
     * @test
     */
    public function an_activatable_stable_that_cannot_be_activated_throws_an_exception()
    {
        $stableMock = $this->mock(Stable::class);
        $repositoryMock = $this->mock(StableRepository::class);
        $controller = new ActivateController;

        $stableMock->expects()->canBeActivated()->andReturns(false);
        $repositoryMock->shouldNotReceive('activate');

        $this->expectException(CannotBeActivatedException::class);

        $controller->__invoke($stableMock, new ActivateRequest, $repositoryMock);
    }
}
