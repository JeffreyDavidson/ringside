<?php

namespace Tests\Integration\Services;

use App\Models\Employment;
use App\Models\Manager;
use App\Repositories\ManagerRepository;
use App\Services\ManagerService;
use App\Strategies\ClearInjury\ManagerClearInjuryStrategy;
use App\Strategies\Employment\ManagerEmploymentStrategy;
use App\Strategies\Injure\ManagerInjuryStrategy;
use App\Strategies\Reinstate\ManagerReinstateStrategy;
use App\Strategies\Release\ManagerReleaseStrategy;
use App\Strategies\Retirement\ManagerRetirementStrategy;
use App\Strategies\Suspend\ManagerSuspendStrategy;
use App\Strategies\Unretire\ManagerUnretireStrategy;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group managers
 * @group services
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
        $repositoryMock = $this->mock(ManagerRepository::class);
        $strategyMock = $this->mock(ManagerEmploymentStrategy::class);
        $service = new ManagerService($repositoryMock);

        $repositoryMock->expects()->create($data)->once()->andReturns($manager);
        $strategyMock->expects()->setEmployable($manager)->once()->andReturns($strategyMock);
        $strategyMock->expects()->employ($employmentDate)->once()->andReturns($strategyMock);

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
        $repositoryMock = $this->mock(ManagerRepository::class);
        $strategyMock = $this->mock(ManagerEmploymentStrategy::class);
        $service = new ManagerService($repositoryMock);

        $repositoryMock->expects()->create($data)->once();
        $strategyMock->shouldNotReceive('employ');

        $service->create($data);
    }

    /**
     * @test
     */
    public function it_can_update_a_manager_without_an_employment_start_date()
    {
        $data = [
            'first_name' => 'Joe',
            'last_name' => 'Smith',
        ];
        $manager = Manager::factory()->make();
        $repositoryMock = $this->mock(ManagerRepository::class);
        $service = new ManagerService($repositoryMock);

        $repositoryMock->expects()->update($manager, $data)->once()->andReturns($manager);
        // Expect a call to not be made to employOrUpdateEmployment

        $service->update($manager, $data);
    }

    /**
     * @test
     */
    public function it_can_update_a_manager_and_employ_if_started_at_is_filled()
    {
        $data = [
            'first_name' => 'Joe',
            'last_name' => 'Smith',
            'started_at' => $employmentDate = Carbon::now()->toDateTimeString(),
        ];
        $manager = Manager::factory()->make();
        $repositoryMock = $this->mock(ManagerRepository::class);
        $service = new ManagerService($repositoryMock);

        $repositoryMock->expects()->update($manager, $data)->once()->andReturns($manager);
        // Expect a call to employOrUpdateEmployment with $employmentDate

        $service->update($manager, $data);
    }

    /**
     * @test
     */
    public function it_can_employ_a_manager_that_is_not_in_employment()
    {
        $manager = Manager::factory()->make();
        $employmentDate = Carbon::now()->addWeek()->toDateTimeString();
        Employment::factory()->make(['employable_id' => 1, ['started_at' => $employmentDate]]);
        $repositoryMock = $this->mock(ManagerRepository::class);
        $serviceMock = $this->mock(ManagerEmploymentStrategy::class);
        $service = new ManagerService($repositoryMock);

        $serviceMock->expects()->setEmployable($manager)->once()->andReturns($serviceMock);
        $serviceMock->expects()->employ($employmentDate)->once()->andReturns($serviceMock);

        $service->employOrUpdateEmployment($manager, $employmentDate);
    }

    /**
     * @test
     */
    public function it_can_update_a_manager_employment_date_when_manager_has_future_employment()
    {
        $manager = Manager::factory()->make();
        $employmentDate = Carbon::now()->addWeek()->toDateTimeString();
        Employment::factory()->make(['employable_id' => 1, ['started_at' => $employmentDate]]);
        $repositoryMock = $this->mock(ManagerRepository::class);
        $serviceMock = $this->mock(ManagerEmploymentStrategy::class);
        $service = new ManagerService($repositoryMock);

        $serviceMock->expects()->setEmployable($manager)->once()->andReturns($serviceMock);
        $serviceMock->expects()->employ($employmentDate)->once()->andReturns($serviceMock);

        $service->employOrUpdateEmployment($manager, $employmentDate);
    }

    /**
     * @test
     */
    public function it_can_delete_a_manager()
    {
        $manager = Manager::factory()->make();
        $repositoryMock = $this->mock(ManagerRepository::class);
        $service = new ManagerService($repositoryMock);

        $repositoryMock->expects()->delete($manager)->once();

        $service->delete($manager);
    }

    /**
     * @test
     */
    public function it_can_restore_a_manager()
    {
        $manager = new Manager;
        $repositoryMock = $this->mock(ManagerRepository::class);
        $service = new ManagerService($repositoryMock);

        $repositoryMock->expects()->restore($manager)->once();

        $service->restore($manager);
    }

    /**
     * @test
     */
    public function it_can_employ_a_manager()
    {
        $manager = new Manager();
        $strategyMock = $this->mock(ManagerEmploymentStrategy::class);
        $repositoryMock = $this->mock(ManagerRepository::class);
        $service = new ManagerService($repositoryMock);

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
        $repositoryMock = $this->mock(ManagerRepository::class);
        $service = new ManagerService($repositoryMock);

        $strategyMock->expects()->setReleasable($manager)->once()->andReturns($strategyMock);
        $strategyMock->expects()->release()->once();

        $service->release($manager);
    }

    /**
     * @test
     */
    public function it_can_injure_a_manager()
    {
        $manager = new Manager();
        $strategyMock = $this->mock(ManagerInjuryStrategy::class);
        $repositoryMock = $this->mock(ManagerRepository::class);
        $service = new ManagerService($repositoryMock);

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
        $repositoryMock = $this->mock(ManagerRepository::class);
        $service = new ManagerService($repositoryMock);

        $strategyMock->expects()->setInjurable($manager)->once()->andReturns($strategyMock);
        $strategyMock->expects()->clearInjury()->once();

        $service->clearFromInjury($manager);
    }

    /**
     * @test
     */
    public function it_can_suspend_a_manager()
    {
        $manager = new Manager();
        $strategyMock = $this->mock(ManagerSuspendStrategy::class);
        $repositoryMock = $this->mock(ManagerRepository::class);
        $service = new ManagerService($repositoryMock);

        $strategyMock->expects()->setSuspendable($manager)->once()->andReturns($strategyMock);
        $strategyMock->expects()->suspend()->once();

        $service->suspend($manager);
    }

    /**
     * @test
     */
    public function it_can_reinstate_a_manager()
    {
        $manager = new Manager();
        $strategyMock = $this->mock(ManagerReinstateStrategy::class);
        $repositoryMock = $this->mock(ManagerRepository::class);
        $service = new ManagerService($repositoryMock);

        $strategyMock->expects()->setReinstatable($manager)->once()->andReturns($strategyMock);
        $strategyMock->expects()->reinstate()->once();

        $service->reinstate($manager);
    }

    /**
     * @test
     */
    public function it_can_retire_a_manager()
    {
        $manager = new Manager();
        $strategyMock = $this->mock(ManagerRetirementStrategy::class);
        $repositoryMock = $this->mock(ManagerRepository::class);
        $service = new ManagerService($repositoryMock);

        $strategyMock->expects()->setRetirable($manager)->once()->andReturns($strategyMock);
        $strategyMock->expects()->retire()->once();

        $service->retire($manager);
    }

    /**
     * @test
     */
    public function it_can_unretire_a_manager()
    {
        $manager = new Manager();
        $strategyMock = $this->mock(ManagerUnretireStrategy::class);
        $repositoryMock = $this->mock(ManagerRepository::class);
        $service = new ManagerService($repositoryMock);

        $strategyMock->expects()->setUnretirable($manager)->once()->andReturns($strategyMock);
        $strategyMock->expects()->unretire()->once();

        $service->unretire($manager);
    }
}
