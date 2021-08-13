<?php

namespace Tests\Integration\Services;

use App\Models\Activation;
use App\Models\Stable;
use App\Repositories\StableRepository;
use App\Services\StableService;
use App\Strategies\Activation\StableActivationStrategy;
use App\Strategies\Deactivation\StableDeactivationStrategy;
use App\Strategies\Retirement\StableRetirementStrategy;
use App\Strategies\Unretire\StableUnretireStrategy;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group stables
 * @group services
 */
class StableServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_can_create_a_stable_with_an_activation()
    {
        $data = [
            'name' => 'Example Stable',
            'activated_at' => $activationDate = Carbon::now()->toDateTimeString(),
        ];
        $stable = Stable::factory()->make(['name' => 'Example Stable']);
        $repositoryMock = $this->mock(StableRepository::class);
        $strategyMock = $this->mock(StableActivationStrategy::class);
        $service = new StableService($repositoryMock);

        $repositoryMock->expects()->create($data)->once()->andReturns($stable);
        $strategyMock->expects()->setActivatable($stable)->once()->andReturns($strategyMock);
        $strategyMock->expects()->activate($activationDate)->once()->andReturns($strategyMock);

        $service->create($data);
    }

    /**
     * @test
     */
    public function it_can_create_a_stable_without_an_activation()
    {
        $data = [
            'name' => 'Example Stable',
        ];
        $repositoryMock = $this->mock(StableRepository::class);
        $strategyMock = $this->mock(StableActivationStrategy::class);
        $service = new StableService($repositoryMock);

        $repositoryMock->expects()->create($data)->once();
        $strategyMock->shouldNotReceive('employ');

        $service->create($data);
    }

    /**
     * @test
     */
    public function it_can_update_a_stable_without_an_activation_start_date()
    {
        $data = [
            'name' => 'Example Name',
        ];
        $stable = Stable::factory()->make();
        $repositoryMock = $this->mock(StableRepository::class);
        $service = new StableService($repositoryMock);

        $repositoryMock->expects()->update($stable, $data)->once()->andReturns($stable);
        // Expect a call to not be made to employOrUpdateActivation

        $service->update($stable, $data);
    }

    /**
     * @test
     */
    public function it_can_update_a_stable_and_employ_if_started_at_is_filled()
    {
        $data = [
            'name' => 'Example Stable',
            'activated_at' => $activationDate = Carbon::now()->toDateTimeString(),
        ];
        $stable = Stable::factory()->make();
        $repositoryMock = $this->mock(StableRepository::class);
        $service = new StableService($repositoryMock);

        $repositoryMock->expects()->update($stable, $data)->once()->andReturns($stable);
        // Expect a call to employOrUpdateActivation with $activationDate

        $service->update($stable, $data);
    }

    /**
     * @test
     */
    public function it_can_activate_a_stable_that_is_not_in_activation()
    {
        $stable = Stable::factory()->make();
        $activationDate = Carbon::now()->addWeek()->toDateTimeString();
        Activation::factory()->make(['activatable_id' => 1, ['started_at' => $activationDate]]);
        $repositoryMock = $this->mock(StableRepository::class);
        $serviceMock = $this->mock(StableActivationStrategy::class);
        $service = new StableService($repositoryMock);

        $serviceMock->expects()->setActivatable($stable)->once()->andReturns($serviceMock);
        $serviceMock->expects()->activate($activationDate)->once()->andReturns($serviceMock);

        $service->activateOrUpdateActivation($stable, $activationDate);
    }

    /**
     * @test
     */
    public function it_can_update_a_stable_activation_date_when_stable_has_future_activation()
    {
        $stable = Stable::factory()->make();
        $activationDate = Carbon::now()->addWeek()->toDateTimeString();
        Activation::factory()->make(['activatable_id' => 1, ['started_at' => $activationDate]]);
        $repositoryMock = $this->mock(StableRepository::class);
        $serviceMock = $this->mock(StableActivationStrategy::class);
        $service = new StableService($repositoryMock);

        $serviceMock->expects()->setActivatable($stable)->once()->andReturns($serviceMock);
        $serviceMock->expects()->activate($activationDate)->once()->andReturns($serviceMock);

        $service->activateOrUpdateActivation($stable, $activationDate);
    }

    /**
     * @test
     */
    public function it_can_delete_a_stable()
    {
        $stable = Stable::factory()->make();
        $repositoryMock = $this->mock(StableRepository::class);
        $service = new StableService($repositoryMock);

        $repositoryMock->expects()->delete($stable)->once();

        $service->delete($stable);
    }

    /**
     * @test
     */
    public function it_can_restore_a_stable()
    {
        $stable = new Stable;
        $repositoryMock = $this->mock(StableRepository::class);
        $service = new StableService($repositoryMock);

        $repositoryMock->expects()->restore($stable)->once();

        $service->restore($stable);
    }

    /**
     * @test
     */
    public function it_can_activate_a_stable()
    {
        $stable = new Stable();
        $strategyMock = $this->mock(StableActivationStrategy::class);
        $repositoryMock = $this->mock(StableRepository::class);
        $service = new StableService($repositoryMock);

        $strategyMock->expects()->setActivatable($stable)->once()->andReturns($strategyMock);
        $strategyMock->expects()->activate()->once();

        $service->activate($stable);
    }

    /**
     * @test
     */
    public function it_can_deactivate_a_stable()
    {
        $stable = new Stable();
        $strategyMock = $this->mock(StableDeactivationStrategy::class);
        $repositoryMock = $this->mock(StableRepository::class);
        $service = new StableService($repositoryMock);

        $strategyMock->expects()->setDeactivatable($stable)->once()->andReturns($strategyMock);
        $strategyMock->expects()->deactivate()->once();

        $service->deactivate($stable);
    }

    /**
     * @test
     */
    public function it_can_retire_a_stable()
    {
        $stable = new Stable();
        $strategyMock = $this->mock(StableRetirementStrategy::class);
        $repositoryMock = $this->mock(StableRepository::class);
        $service = new StableService($repositoryMock);

        $strategyMock->expects()->setRetirable($stable)->once()->andReturns($strategyMock);
        $strategyMock->expects()->retire()->once();

        $service->retire($stable);
    }

    /**
     * @test
     */
    public function it_can_unretire_a_stable()
    {
        $stable = new Stable();
        $strategyMock = $this->mock(StableUnretireStrategy::class);
        $repositoryMock = $this->mock(StableRepository::class);
        $service = new StableService($repositoryMock);

        $strategyMock->expects()->setUnretirable($stable)->once()->andReturns($strategyMock);
        $strategyMock->expects()->unretire()->once();

        $service->unretire($stable);
    }
}
