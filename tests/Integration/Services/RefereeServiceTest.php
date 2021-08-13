<?php

namespace Tests\Integration\Services;

use App\Models\Employment;
use App\Models\Referee;
use App\Repositories\RefereeRepository;
use App\Services\RefereeService;
use App\Strategies\ClearInjury\RefereeClearInjuryStrategy;
use App\Strategies\Employment\RefereeEmploymentStrategy;
use App\Strategies\Injure\RefereeInjuryStrategy;
use App\Strategies\Reinstate\RefereeReinstateStrategy;
use App\Strategies\Release\RefereeReleaseStrategy;
use App\Strategies\Retirement\RefereeRetirementStrategy;
use App\Strategies\Suspend\RefereeSuspendStrategy;
use App\Strategies\Unretire\RefereeUnretireStrategy;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group referees
 * @group services
 */
class RefereeServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_can_create_a_referee_with_an_employment()
    {
        $data = [
            'first_name' => 'Joe',
            'last_name' => 'Smith',
            'started_at' => $employmentDate = Carbon::now()->toDateTimeString(),
        ];
        $referee = Referee::factory()->make(['first_name' => 'Joe', 'last_name' => 'Smith']);
        $repositoryMock = $this->mock(RefereeRepository::class);
        $strategyMock = $this->mock(RefereeEmploymentStrategy::class);
        $service = new RefereeService($repositoryMock);

        $repositoryMock->expects()->create($data)->once()->andReturns($referee);
        $strategyMock->expects()->setEmployable($referee)->once()->andReturns($strategyMock);
        $strategyMock->expects()->employ($employmentDate)->once()->andReturns($strategyMock);

        $service->create($data);
    }

    /**
     * @test
     */
    public function it_can_create_a_referee_without_an_employment()
    {
        $data = [
            'first_name' => 'Joe',
            'last_name' => 'Smith',
        ];
        $repositoryMock = $this->mock(RefereeRepository::class);
        $strategyMock = $this->mock(RefereeEmploymentStrategy::class);
        $service = new RefereeService($repositoryMock);

        $repositoryMock->expects()->create($data)->once();
        $strategyMock->shouldNotReceive('employ');

        $service->create($data);
    }

    /**
     * @test
     */
    public function it_can_update_a_referee_without_an_employment_start_date()
    {
        $data = [
            'first_name' => 'Joe',
            'last_name' => 'Smith',
        ];
        $referee = Referee::factory()->make();
        $repositoryMock = $this->mock(RefereeRepository::class);
        $service = new RefereeService($repositoryMock);

        $repositoryMock->expects()->update($referee, $data)->once()->andReturns($referee);
        // Expect a call to not be made to employOrUpdateEmployment

        $service->update($referee, $data);
    }

    /**
     * @test
     */
    public function it_can_update_a_referee_and_employ_if_started_at_is_filled()
    {
        $data = [
            'first_name' => 'Joe',
            'last_name' => 'Smith',
            'started_at' => $employmentDate = Carbon::now()->toDateTimeString(),
        ];
        $referee = Referee::factory()->make();
        $repositoryMock = $this->mock(RefereeRepository::class);
        $service = new RefereeService($repositoryMock);

        $repositoryMock->expects()->update($referee, $data)->once()->andReturns($referee);
        // Expect a call to employOrUpdateEmployment with $employmentDate

        $service->update($referee, $data);
    }

    /**
     * @test
     */
    public function it_can_employ_a_referee_that_is_not_in_employment()
    {
        $referee = Referee::factory()->make();
        $employmentDate = Carbon::now()->addWeek()->toDateTimeString();
        Employment::factory()->make(['employable_id' => 1, ['started_at' => $employmentDate]]);
        $repositoryMock = $this->mock(RefereeRepository::class);
        $serviceMock = $this->mock(RefereeEmploymentStrategy::class);
        $service = new RefereeService($repositoryMock);

        $serviceMock->expects()->setEmployable($referee)->once()->andReturns($serviceMock);
        $serviceMock->expects()->employ($employmentDate)->once()->andReturns($serviceMock);

        $service->employOrUpdateEmployment($referee, $employmentDate);
    }

    /**
     * @test
     */
    public function it_can_update_a_referee_employment_date_when_referee_has_future_employment()
    {
        $referee = Referee::factory()->make();
        $employmentDate = Carbon::now()->addWeek()->toDateTimeString();
        Employment::factory()->make(['employable_id' => 1, ['started_at' => $employmentDate]]);
        $repositoryMock = $this->mock(RefereeRepository::class);
        $serviceMock = $this->mock(RefereeEmploymentStrategy::class);
        $service = new RefereeService($repositoryMock);

        $serviceMock->expects()->setEmployable($referee)->once()->andReturns($serviceMock);
        $serviceMock->expects()->employ($employmentDate)->once()->andReturns($serviceMock);

        $service->employOrUpdateEmployment($referee, $employmentDate);
    }

    /**
     * @test
     */
    public function it_can_delete_a_referee()
    {
        $referee = Referee::factory()->make();
        $repositoryMock = $this->mock(RefereeRepository::class);
        $service = new RefereeService($repositoryMock);

        $repositoryMock->expects()->delete($referee)->once();

        $service->delete($referee);
    }

    /**
     * @test
     */
    public function it_can_restore_a_referee()
    {
        $referee = new Referee;
        $repositoryMock = $this->mock(RefereeRepository::class);
        $service = new RefereeService($repositoryMock);

        $repositoryMock->expects()->restore($referee)->once();

        $service->restore($referee);
    }

    /**
     * @test
     */
    public function it_can_employ_a_referee()
    {
        $referee = new Referee();
        $strategyMock = $this->mock(RefereeEmploymentStrategy::class);
        $repositoryMock = $this->mock(RefereeRepository::class);
        $service = new RefereeService($repositoryMock);

        $strategyMock->expects()->setEmployable($referee)->once()->andReturns($strategyMock);
        $strategyMock->expects()->employ()->once();

        $service->employ($referee);
    }

    /**
     * @test
     */
    public function it_can_release_a_referee()
    {
        $referee = new Referee();
        $strategyMock = $this->mock(RefereeReleaseStrategy::class);
        $repositoryMock = $this->mock(RefereeRepository::class);
        $service = new RefereeService($repositoryMock);

        $strategyMock->expects()->setReleasable($referee)->once()->andReturns($strategyMock);
        $strategyMock->expects()->release()->once();

        $service->release($referee);
    }

    /**
     * @test
     */
    public function it_can_injure_a_referee()
    {
        $referee = new Referee();
        $strategyMock = $this->mock(RefereeInjuryStrategy::class);
        $repositoryMock = $this->mock(RefereeRepository::class);
        $service = new RefereeService($repositoryMock);

        $strategyMock->expects()->setInjurable($referee)->once()->andReturns($strategyMock);
        $strategyMock->expects()->injure()->once();

        $service->injure($referee);
    }

    /**
     * @test
     */
    public function it_can_clear_an_injury_of_a_referee()
    {
        $referee = new Referee();
        $strategyMock = $this->mock(RefereeClearInjuryStrategy::class);
        $repositoryMock = $this->mock(RefereeRepository::class);
        $service = new RefereeService($repositoryMock);

        $strategyMock->expects()->setInjurable($referee)->once()->andReturns($strategyMock);
        $strategyMock->expects()->clearInjury()->once();

        $service->clearFromInjury($referee);
    }

    /**
     * @test
     */
    public function it_can_suspend_a_referee()
    {
        $referee = new Referee();
        $strategyMock = $this->mock(RefereeSuspendStrategy::class);
        $repositoryMock = $this->mock(RefereeRepository::class);
        $service = new RefereeService($repositoryMock);

        $strategyMock->expects()->setSuspendable($referee)->once()->andReturns($strategyMock);
        $strategyMock->expects()->suspend()->once();

        $service->suspend($referee);
    }

    /**
     * @test
     */
    public function it_can_reinstate_a_referee()
    {
        $referee = new Referee();
        $strategyMock = $this->mock(RefereeReinstateStrategy::class);
        $repositoryMock = $this->mock(RefereeRepository::class);
        $service = new RefereeService($repositoryMock);

        $strategyMock->expects()->setReinstatable($referee)->once()->andReturns($strategyMock);
        $strategyMock->expects()->reinstate()->once();

        $service->reinstate($referee);
    }

    /**
     * @test
     */
    public function it_can_retire_a_referee()
    {
        $referee = new Referee();
        $strategyMock = $this->mock(RefereeRetirementStrategy::class);
        $repositoryMock = $this->mock(RefereeRepository::class);
        $service = new RefereeService($repositoryMock);

        $strategyMock->expects()->setRetirable($referee)->once()->andReturns($strategyMock);
        $strategyMock->expects()->retire()->once();

        $service->retire($referee);
    }

    /**
     * @test
     */
    public function it_can_unretire_a_referee()
    {
        $referee = new Referee();
        $strategyMock = $this->mock(RefereeUnretireStrategy::class);
        $repositoryMock = $this->mock(RefereeRepository::class);
        $service = new RefereeService($repositoryMock);

        $strategyMock->expects()->setUnretirable($referee)->once()->andReturns($strategyMock);
        $strategyMock->expects()->unretire()->once();

        $service->unretire($referee);
    }
}
