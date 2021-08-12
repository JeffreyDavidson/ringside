<?php

namespace Tests\Integration\Services;

use App\Models\Manager;
use App\Repositories\ManagerRepository;
use App\Services\ManagerService;
use App\Strategies\ClearInjury\ManagerClearInjuryStrategy;
use App\Strategies\Employment\ManagerEmploymentStrategy;
use App\Strategies\Injure\ManagerInjuryStrategy;
use App\Strategies\Release\ManagerReleaseStrategy;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
    public function it_can_create_a_manager_with_an_employment()
    {
        $data = [
            'first_name' => 'Joe',
            'last_name' => 'Smith',
            'started_at' => $employmentDate = Carbon::now()->toDateTimeString(),
        ];
        $manager = Manager::factory()->make(['first_name' => 'Joe', 'last_name' => 'Smith']);
        $managerRepositoryMock = $this->mock(ManagerRepository::class);
        $managerEmploymentStrategyMock = $this->mock(ManagerEmploymentStrategy::class);
        $service = new ManagerService($managerRepositoryMock);

        $managerRepositoryMock->expects()->create($data)->once()->andReturns($manager);
        $managerEmploymentStrategyMock->expects()->setEmployable($manager)->once()->andReturns($managerEmploymentStrategyMock);
        $managerEmploymentStrategyMock->expects()->employ($employmentDate)->once()->andReturns($managerEmploymentStrategyMock);

        $service->create($data);
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
        $managerRepositoryMock = $this->mock(ManagerRepository::class);
        $service = new ManagerService($managerRepositoryMock);

        $managerRepositoryMock->expects()->create($data)->once();

        $service->create($data);
    }

    /**
     * @test
     */
    public function it_can_delete_a_manager()
    {
        $manager = Manager::factory()->make();
        $managerRepositoryMock = $this->mock(ManagerRepository::class);
        $service = new ManagerService($managerRepositoryMock);

        $managerRepositoryMock->expects()->delete($manager)->once();

        $service->delete($manager);
    }

    /**
     * @test
     */
    public function it_can_restore_a_manager()
    {
        $manager = new Manager;
        $managerRepositoryMock = $this->mock(ManagerRepository::class);
        $service = new ManagerService($managerRepositoryMock);

        $managerRepositoryMock->expects()->restore($manager)->once();

        $service->restore($manager);
    }

    /**
     * @test
     */
    public function it_can_injure_a_manager()
    {
        $manager = new Manager();
        $strategyMock = $this->mock(ManagerInjuryStrategy::class);
        $managerRepositoryMock = $this->mock(ManagerRepository::class);
        $service = new ManagerService($managerRepositoryMock);

        $strategyMock->expects()->setInjurable($manager)->once()->andReturns($strategyMock);
        $strategyMock->expects()->injure()->once();

        $service->injure($manager);
    }

    /**
     * @test
     */
    public function it_can_clear_an_injury_of_a_manager()
    {
        $manager = new Manager();
        $strategyMock = $this->mock(ManagerClearInjuryStrategy::class);
        $managerRepositoryMock = $this->mock(ManagerRepository::class);
        $service = new ManagerService($managerRepositoryMock);

        $strategyMock->expects()->setInjurable($manager)->once()->andReturns($strategyMock);
        $strategyMock->expects()->clearInjury()->once();

        $service->clearFromInjury($manager);
    }

    /**
     * @test
     */
    public function it_can_employ_a_manager()
    {
        $manager = new Manager();
        $strategyMock = $this->mock(ManagerEmploymentStrategy::class);
        $managerRepositoryMock = $this->mock(ManagerRepository::class);
        $service = new ManagerService($managerRepositoryMock);

        $strategyMock->expects()->setEmployable($manager)->once()->andReturns($strategyMock);
        $strategyMock->expects()->employ()->once();

        $service->employ($manager);
    }

    /**
     * @test
     */
    public function it_can_release_a_manager()
    {
        $manager = new Manager();
        $strategyMock = $this->mock(ManagerReleaseStrategy::class);
        $managerRepositoryMock = $this->mock(ManagerRepository::class);
        $service = new ManagerService($managerRepositoryMock);

        $strategyMock->expects()->setReleasable($manager)->once()->andReturns($strategyMock);
        $strategyMock->expects()->release()->once();

        $service->release($manager);
    }
}
