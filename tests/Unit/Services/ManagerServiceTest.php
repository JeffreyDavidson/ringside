<?php

namespace Tests\Unit\Services;

use App\Models\Manager;
use App\Repositories\ManagerRepository;
use App\Services\ManagerService;
use Tests\TestCase;

/**
 * @group managers
 * @group roster
 * @group services
 */
class ManagerServiceTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_create_a_manager_with_an_employment()
    {
        $data = [];
        $managerMock = $this->mock(Manager::class);
        $repositoryMock = $this->mock(ManagerRepository::class);
        $service = new ManagerService($repositoryMock);

        $repositoryMock->expects()->create($data)->once()->andReturns($managerMock);
        $repositoryMock->expects()->employ($managerMock, $data['started_at'])->once()->andReturns($managerMock);

        $service->create($data);
    }

    /**
     * @test
     */
    public function it_can_create_a_manager_without_an_employment()
    {
        $data = [];
        $repositoryMock = $this->mock(ManagerRepository::class);
        $service = new ManagerService($repositoryMock);

        $repositoryMock->expects()->create($data)->once();
        $repositoryMock->shouldNotReceive('employ');

        $service->create($data);
    }

    /**
     * @test
     */
    public function it_can_update_a_manager_without_an_employment_start_date()
    {
        $data = [];
        $managerMock = $this->mock(Manager::class);
        $repositoryMock = $this->mock(ManagerRepository::class);
        $service = new ManagerService($repositoryMock);

        $repositoryMock->expects()->update($managerMock, $data)->once()->andReturns($managerMock);

        $service->update($managerMock, $data);
    }

    /**
     * @test
     */
    public function it_can_update_a_manager_and_employ_if_started_at_is_filled()
    {
        $data = [];
        $managerMock = $this->mock(Manager::class);
        $repositoryMock = $this->mock(ManagerRepository::class);
        $service = new ManagerService($repositoryMock);

        $repositoryMock->expects()->update($managerMock, $data)->once()->andReturns($managerMock);
        $managerMock->expects()->isNotInEmployment()->once()->andReturns(true);
        $repositoryMock->expects()->employ($managerMock, $data['started_at'])->once()->andReturns($managerMock);

        $service->update($managerMock, $data);
    }

    /**
     * @test
     */
    public function it_can_employ_a_manager_that_is_not_in_employment()
    {
        $managerMock = $this->mock(Manager::class);
        $repositoryMock = $this->mock(ManagerRepository::class);
        $service = new ManagerService($repositoryMock);

        $managerMock->expects()->isNotInEmployment()->once()->andReturns(true);
        $repositoryMock->expects()->employ($managerMock, now()->toDateTimeString())->once()->andReturns($managerMock);

        $service->employOrUpdateEmployment($managerMock, now()->toDateTimeString());
    }

    /**
     * @test
     */
    public function it_can_update_a_manager_employment_date_when_manager_has_future_employment()
    {
        $managerMock = $this->mock(Manager::class);
        $repositoryMock = $this->mock(ManagerRepository::class);
        $service = new ManagerService($repositoryMock);

        $managerMock->expects()->isNotInEmployment()->once()->andReturns(false);
        $repositoryMock->shouldNotHaveReceived('employ');
        $managerMock->expects()->hasFutureEmployment()->once()->andReturns(true);
        $managerMock->expects()->employedOn(now()->toDateTimeString())->once()->andReturns(false);
        $repositoryMock->expects()->updateEmployment($managerMock, now()->toDateTimeString());

        $service->employOrUpdateEmployment($managerMock, now()->toDateTimeString());
    }

    /**
     * @test
     */
    public function it_can_delete_a_manager()
    {
        $managerMock = $this->mock(Manager::class);
        $repositoryMock = $this->mock(ManagerRepository::class);
        $service = new ManagerService($repositoryMock);

        $repositoryMock->expects()->delete($managerMock)->once();

        $service->delete($managerMock);
    }

    /**
     * @test
     */
    public function it_can_restore_a_manager()
    {
        $managerMock = $this->mock(Manager::class);
        $repositoryMock = $this->mock(ManagerRepository::class);
        $service = new ManagerService($repositoryMock);

        $repositoryMock->expects()->restore($managerMock)->once();

        $service->restore($managerMock);
    }
}
