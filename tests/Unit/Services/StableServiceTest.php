<?php

namespace Tests\Unit\Services;

use App\Models\Stable;
use App\Repositories\StableRepository;
use App\Services\StableService;
use Tests\TestCase;

/**
 * @group stables
 * @group roster
 * @group services
 */
class StableServiceTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_create_a_stable_with_an_activation()
    {
        $stableMock = $this->mock(Stable::class);
        $repositoryMock = $this->mock(StableRepository::class);
        $service = new StableService($repositoryMock);

        $repositoryMock->expects()->create(typeOf('array'))->once()->andReturns($stableMock);

        $service->create([]);
    }

    /**
     * @test
     */
    public function it_can_create_a_stable_without_an_activation()
    {
        $repositoryMock = $this->mock(StableRepository::class);
        $service = new StableService($repositoryMock);

        $repositoryMock->expects()->create($data)->once();
        $repositoryMock->shouldNotReceive('employ');

        $service->create($data);
    }

    /**
     * @test
     */
    public function it_can_update_a_stable_without_an_activation_start_date()
    {
        $stableMock = $this->mock(Stable::class);
        $repositoryMock = $this->mock(StableRepository::class);
        $service = new StableService($repositoryMock);

        $repositoryMock->expects()->update($stableMock, $data)->once()->andReturns($stableMock);
        // Expect a call to not be made to employOrUpdateActivation

        $service->update($stableMock, $data);
    }

    /**
     * @test
     */
    public function it_can_update_a_stable_and_activate_if_started_at_is_filled()
    {
        $stableMock = $this->mock(Stable::class);
        $repositoryMock = $this->mock(StableRepository::class);
        $service = new StableService($repositoryMock);

        $repositoryMock->expects()->update($stableMock, $data)->once()->andReturns($stableMock);
        // Expect a call to employOrUpdateActivation with $activationDate

        $service->update($stableMock, $data);
    }

    /**
     * @test
     */
    public function it_can_activate_a_stable_that_is_not_in_activation()
    {
        $stableMock = $this->mock(Stable::class);
        $repositoryMock = $this->mock(StableRepository::class);
        $service = new StableService($repositoryMock);

        $stableMock->expects()->isNotInActivation()->once()->andReturns(true);
        $repositoryMock->expects()->activate($stableMock, now()->toDateTimeString())->once()->andReturns($stableMock);

        $service->activateOrUpdateActivation($stableMock, now()->toDateTimeString());
    }

    /**
     * @test
     */
    public function it_can_update_a_stable_activation_date_when_stable_has_future_activation()
    {
        $stableMock = $this->mock(Stable::class);
        $repositoryMock = $this->mock(StableRepository::class);
        $service = new StableService($repositoryMock);

        $stableMock->expects()->isNotInActivation()->once()->andReturns(false);
        $stableMock->expects()->hasFutureActivation()->once()->andReturns(true);
        $stableMock->expects()->activatedOn(now()->toDateTimeString())->once()->andReturns(false);
        $repositoryMock->expects()->updateActivation($stableMock, now()->toDateTimeString())->once()->andReturns($stableMock);

        $service->activateOrUpdateActivation($stableMock, now()->toDateTimeString());
    }

    /**
     * @test
     */
    public function it_can_delete_a_stable()
    {
        $stableMock = $this->mock(Stable::class);
        $repositoryMock = $this->mock(StableRepository::class);
        $service = new StableService($repositoryMock);

        $repositoryMock->expects()->delete($stableMock)->once();

        $service->delete($stableMock);
    }

    /**
     * @test
     */
    public function it_can_restore_a_stable()
    {
        $stableMock = $this->mock(Stable::class);
        $repositoryMock = $this->mock(StableRepository::class);
        $service = new StableService($repositoryMock);

        $repositoryMock->expects()->restore($stableMock)->once();

        $service->restore($stableMock);
    }
}
