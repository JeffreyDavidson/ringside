<?php

namespace Tests\Integration\Services;

use App\Models\Employment;
use App\Models\Manager;
use App\Repositories\ManagerRepository;
use App\Services\ManagerService;
use App\Strategies\ClearInjury\ManagerClearInjuryStrategy;
use App\Strategies\Employment\ManagerEmploymentStrategy;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

/**
 * @group managers
 */
class ManagerServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_can_create_a_manager()
    {
        $data = [
            'first_name' => 'Joe',
            'last_name' => 'Smith',
            'started_at' => $employmentDate = Carbon::now()->toDateTimeString(),
        ];

        $manager = Manager::factory()->make(['id' => 1, 'first_name' => 'Joe', 'last_name' => 'Smith']);

        $managerRepositoryMock = $this->mock(ManagerRepository::class)->shouldReceive('create')->with($data)->andReturn($manager)->getMock();
        $managerEmploymentStrategyMock = $this->mock(ManagerEmploymentStrategy::class)->shouldReceive('employ')->withArgs([$manager, $employmentDate]);

        $this->app->instance(ManagerRepository::class, $managerRepositoryMock);
        $this->app->instance(ManagerEmploymentStrategy::class, $managerEmploymentStrategyMock);

        (new ManagerService($managerRepositoryMock))->create($data);
    }

    /**
     * @test
     */
    public function it_can_create_a_manager_without_an_employment()
    {
        $data = [
            'first_name' => 'Joe',
            'last_name' => 'Smith',
        ];

        $manager = Manager::factory()->make(['first_name' => 'Joe', 'last_name' => 'Smith']);

        $managerRepositoryMock = $this->mock(ManagerRepository::class)->shouldReceive('create')->andReturn($manager)->getMock();

        $this->app->instance(ManagerRepository::class, $managerRepositoryMock);

        (new ManagerService($managerRepositoryMock))->create($data);
    }

    /**
     * @test
     */
    public function it_can_delete_a_manager()
    {
        $manager = Manager::factory()->make(['id' => 1, 'first_name' => 'Joe', 'last_name' => 'Smith']);

        $managerRepositoryMock = $this->mock(ManagerRepository::class)->shouldReceive('delete')->with($manager)->andReturnNull()->getMock();

        $this->app->instance(ManagerRepository::class, $managerRepositoryMock);

        (new ManagerService($managerRepositoryMock))->delete($manager);
    }

    /**
     * @test
     */
    public function it_can_restore_a_manager()
    {
        $manager = Manager::factory()->make(['id' => 1, 'first_name' => 'Joe', 'last_name' => 'Smith', 'deleted_at' => Carbon::yesterday()]);

        $managerRepositoryMock = $this->mock(ManagerRepository::class)->shouldReceive('restore')->with($manager)->andReturnNull()->getMock();

        $this->app->instance(ManagerRepository::class, $managerRepositoryMock);

        (new ManagerService($managerRepositoryMock))->restore($manager);
    }

    /**
     * @test
     */
    public function it_can_clear_an_injury_of_a_manager()
    {
        $manager = Manager::factory()->make();

        $managerRepositoryMock = $this->mock(ManagerRepository::class);
        $managerClearInjuryStrategyMock = $this->mock(ManagerClearInjuryStrategy::class, $managerRepositoryMock)->shouldReceive('clearInjury')->once();

        $this->app->instance(ManagerClearInjuryStrategy::class, $managerClearInjuryStrategyMock);

        (new ManagerService($managerRepositoryMock))->clearFromInjury($manager);
    }
}
