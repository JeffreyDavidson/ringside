<?php

namespace Tests\Unit\Http\Controllers\Wrestlers;

use App\Exceptions\CannotBeSuspendedException;
use App\Http\Controllers\Wrestlers\SuspendController;
use App\Http\Requests\Wrestlers\SuspendRequest;
use App\Models\Wrestler;
use App\Repositories\WrestlerRepository;
use Tests\TestCase;

/**
 * @group wrestlers
 * @group controllers
 */
class SuspendControllerTest extends TestCase
{
    /**
     * @test
     */
    public function a_suspendable_wrestler_can_be_suspended_with_a_given_date()
    {
        $suspensionDate = now()->toDateTimeString();
        $wrestlerMock = $this->mock(Wrestler::class);
        $repositoryMock = $this->mock(WrestlerRepository::class);
        $controller = new SuspendController;

        $wrestlerMock->expects()->canBeSuspended()->andReturns(true);
        $repositoryMock->expects()->suspend($wrestlerMock, $suspensionDate)->once()->andReturns();
        $wrestlerMock->expects()->updateStatusAndSave()->once();

        $controller->__invoke($wrestlerMock, new SuspendRequest, $repositoryMock);
    }

    /**
     * @test
     */
    public function a_suspendable_wrestler_that_cannot_be_suspended_throws_an_exception()
    {
        $wrestlerMock = $this->mock(Wrestler::class);
        $repositoryMock = $this->mock(WrestlerRepository::class);
        $controller = new SuspendController;

        $wrestlerMock->expects()->canBeSuspended()->andReturns(false);
        $repositoryMock->shouldNotReceive('suspend');

        $this->expectException(CannotBeSuspendedException::class);

        $controller->__invoke($wrestlerMock, new SuspendRequest, $repositoryMock);
    }
}
