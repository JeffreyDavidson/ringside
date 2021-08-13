<?php

namespace Tests\Integration\Services;

use App\Models\Employment;
use App\Models\Wrestler;
use App\Repositories\WrestlerRepository;
use App\Services\WrestlerService;
use App\Strategies\ClearInjury\WrestlerClearInjuryStrategy;
use App\Strategies\Employment\WrestlerEmploymentStrategy;
use App\Strategies\Injure\WrestlerInjuryStrategy;
use App\Strategies\Reinstate\WrestlerReinstateStrategy;
use App\Strategies\Release\WrestlerReleaseStrategy;
use App\Strategies\Retirement\WrestlerRetirementStrategy;
use App\Strategies\Suspend\WrestlerSuspendStrategy;
use App\Strategies\Unretire\WrestlerUnretireStrategy;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group wrestlers
 * @group services
 */
class WrestlerServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_can_create_a_wrestler_with_an_employment()
    {
        $data = [
            'first_name' => 'Joe',
            'last_name' => 'Smith',
            'started_at' => $employmentDate = Carbon::now()->toDateTimeString(),
        ];
        $wrestler = Wrestler::factory()->make(['first_name' => 'Joe', 'last_name' => 'Smith']);
        $repositoryMock = $this->mock(WrestlerRepository::class);
        $strategyMock = $this->mock(WrestlerEmploymentStrategy::class);
        $service = new WrestlerService($repositoryMock);

        $repositoryMock->expects()->create($data)->once()->andReturns($wrestler);
        $strategyMock->expects()->setEmployable($wrestler)->once()->andReturns($strategyMock);
        $strategyMock->expects()->employ($employmentDate)->once()->andReturns($strategyMock);

        $service->create($data);
    }

    /**
     * @test
     */
    public function it_can_create_a_wrestler_without_an_employment()
    {
        $data = [
            'first_name' => 'Joe',
            'last_name' => 'Smith',
        ];
        $repositoryMock = $this->mock(WrestlerRepository::class);
        $strategyMock = $this->mock(WrestlerEmploymentStrategy::class);
        $service = new WrestlerService($repositoryMock);

        $repositoryMock->expects()->create($data)->once();
        $strategyMock->shouldNotReceive('employ');

        $service->create($data);
    }

    /**
     * @test
     */
    public function it_can_update_a_wrestler_without_an_employment_start_date()
    {
        $data = [
            'first_name' => 'Joe',
            'last_name' => 'Smith',
        ];
        $wrestler = Wrestler::factory()->make();
        $repositoryMock = $this->mock(WrestlerRepository::class);
        $service = new WrestlerService($repositoryMock);

        $repositoryMock->expects()->update($wrestler, $data)->once()->andReturns($wrestler);
        // Expect a call to not be made to employOrUpdateEmployment

        $service->update($wrestler, $data);
    }

    /**
     * @test
     */
    public function it_can_update_a_wrestler_and_employ_if_started_at_is_filled()
    {
        $data = [
            'first_name' => 'Joe',
            'last_name' => 'Smith',
            'started_at' => $employmentDate = Carbon::now()->toDateTimeString(),
        ];
        $wrestler = Wrestler::factory()->make();
        $repositoryMock = $this->mock(WrestlerRepository::class);
        $service = new WrestlerService($repositoryMock);

        $repositoryMock->expects()->update($wrestler, $data)->once()->andReturns($wrestler);
        // Expect a call to employOrUpdateEmployment with $employmentDate

        $service->update($wrestler, $data);
    }

    /**
     * @test
     */
    public function it_can_employ_a_wrestler_that_is_not_in_employment()
    {
        $wrestler = Wrestler::factory()->make();
        $employmentDate = Carbon::now()->addWeek()->toDateTimeString();
        Employment::factory()->make(['employable_id' => 1, ['started_at' => $employmentDate]]);
        $repositoryMock = $this->mock(WrestlerRepository::class);
        $serviceMock = $this->mock(WrestlerEmploymentStrategy::class);
        $service = new WrestlerService($repositoryMock);

        $serviceMock->expects()->setEmployable($wrestler)->once()->andReturns($serviceMock);
        $serviceMock->expects()->employ($employmentDate)->once()->andReturns($serviceMock);

        $service->employOrUpdateEmployment($wrestler, $employmentDate);
    }

    /**
     * @test
     */
    public function it_can_update_a_wrestler_employment_date_when_wrestler_has_future_employment()
    {
        $wrestler = Wrestler::factory()->make();
        $employmentDate = Carbon::now()->addWeek()->toDateTimeString();
        Employment::factory()->make(['employable_id' => 1, ['started_at' => $employmentDate]]);
        $repositoryMock = $this->mock(WrestlerRepository::class);
        $serviceMock = $this->mock(WrestlerEmploymentStrategy::class);
        $service = new WrestlerService($repositoryMock);

        $serviceMock->expects()->setEmployable($wrestler)->once()->andReturns($serviceMock);
        $serviceMock->expects()->employ($employmentDate)->once()->andReturns($serviceMock);

        $service->employOrUpdateEmployment($wrestler, $employmentDate);
    }

    /**
     * @test
     */
    public function it_can_delete_a_wrestler()
    {
        $wrestler = Wrestler::factory()->make();
        $repositoryMock = $this->mock(WrestlerRepository::class);
        $service = new WrestlerService($repositoryMock);

        $repositoryMock->expects()->delete($wrestler)->once();

        $service->delete($wrestler);
    }

    /**
     * @test
     */
    public function it_can_restore_a_wrestler()
    {
        $wrestler = new Wrestler;
        $repositoryMock = $this->mock(WrestlerRepository::class);
        $service = new WrestlerService($repositoryMock);

        $repositoryMock->expects()->restore($wrestler)->once();

        $service->restore($wrestler);
    }

    /**
     * @test
     */
    public function it_can_employ_a_wrestler()
    {
        $wrestler = new Wrestler();
        $strategyMock = $this->mock(WrestlerEmploymentStrategy::class);
        $repositoryMock = $this->mock(WrestlerRepository::class);
        $service = new WrestlerService($repositoryMock);

        $strategyMock->expects()->setEmployable($wrestler)->once()->andReturns($strategyMock);
        $strategyMock->expects()->employ()->once();

        $service->employ($wrestler);
    }

    /**
     * @test
     */
    public function it_can_release_a_wrestler()
    {
        $wrestler = new Wrestler();
        $strategyMock = $this->mock(WrestlerReleaseStrategy::class);
        $repositoryMock = $this->mock(WrestlerRepository::class);
        $service = new WrestlerService($repositoryMock);

        $strategyMock->expects()->setReleasable($wrestler)->once()->andReturns($strategyMock);
        $strategyMock->expects()->release()->once();

        $service->release($wrestler);
    }

    /**
     * @test
     */
    public function it_can_injure_a_wrestler()
    {
        $wrestler = new Wrestler();
        $strategyMock = $this->mock(WrestlerInjuryStrategy::class);
        $repositoryMock = $this->mock(WrestlerRepository::class);
        $service = new WrestlerService($repositoryMock);

        $strategyMock->expects()->setInjurable($wrestler)->once()->andReturns($strategyMock);
        $strategyMock->expects()->injure()->once();

        $service->injure($wrestler);
    }

    /**
     * @test
     */
    public function it_can_clear_an_injury_of_a_wrestler()
    {
        $wrestler = new Wrestler();
        $strategyMock = $this->mock(WrestlerClearInjuryStrategy::class);
        $repositoryMock = $this->mock(WrestlerRepository::class);
        $service = new WrestlerService($repositoryMock);

        $strategyMock->expects()->setInjurable($wrestler)->once()->andReturns($strategyMock);
        $strategyMock->expects()->clearInjury()->once();

        $service->clearFromInjury($wrestler);
    }

    /**
     * @test
     */
    public function it_can_suspend_a_wrestler()
    {
        $wrestler = new Wrestler();
        $strategyMock = $this->mock(WrestlerSuspendStrategy::class);
        $repositoryMock = $this->mock(WrestlerRepository::class);
        $service = new WrestlerService($repositoryMock);

        $strategyMock->expects()->setSuspendable($wrestler)->once()->andReturns($strategyMock);
        $strategyMock->expects()->suspend()->once();

        $service->suspend($wrestler);
    }

    /**
     * @test
     */
    public function it_can_reinstate_a_wrestler()
    {
        $wrestler = new Wrestler();
        $strategyMock = $this->mock(WrestlerReinstateStrategy::class);
        $repositoryMock = $this->mock(WrestlerRepository::class);
        $service = new WrestlerService($repositoryMock);

        $strategyMock->expects()->setReinstatable($wrestler)->once()->andReturns($strategyMock);
        $strategyMock->expects()->reinstate()->once();

        $service->reinstate($wrestler);
    }

    /**
     * @test
     */
    public function it_can_retire_a_wrestler()
    {
        $wrestler = new Wrestler();
        $strategyMock = $this->mock(WrestlerRetirementStrategy::class);
        $repositoryMock = $this->mock(WrestlerRepository::class);
        $service = new WrestlerService($repositoryMock);

        $strategyMock->expects()->setRetirable($wrestler)->once()->andReturns($strategyMock);
        $strategyMock->expects()->retire()->once();

        $service->retire($wrestler);
    }

    /**
     * @test
     */
    public function it_can_unretire_a_wrestler()
    {
        $wrestler = new Wrestler();
        $strategyMock = $this->mock(WrestlerUnretireStrategy::class);
        $repositoryMock = $this->mock(WrestlerRepository::class);
        $service = new WrestlerService($repositoryMock);

        $strategyMock->expects()->setUnretirable($wrestler)->once()->andReturns($strategyMock);
        $strategyMock->expects()->unretire()->once();

        $service->unretire($wrestler);
    }
}
