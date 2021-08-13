<?php

namespace Tests\Integration\Services;

use App\Models\Activation;
use App\Models\Title;
use App\Repositories\TitleRepository;
use App\Services\TitleService;
use App\Strategies\Activation\TitleActivationStrategy;
use App\Strategies\Deactivation\TitleDeactivationStrategy;
use App\Strategies\Retirement\TitleRetirementStrategy;
use App\Strategies\Unretire\TitleUnretireStrategy;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group titles
 * @group services
 */
class TitleServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_can_create_a_title_with_an_activation()
    {
        $data = [
            'name' => 'Example Title',
            'activated_at' => $activationDate = Carbon::now()->toDateTimeString(),
        ];
        $title = Title::factory()->make(['name' => 'Example Title']);
        $repositoryMock = $this->mock(TitleRepository::class);
        $strategyMock = $this->mock(TitleActivationStrategy::class);
        $service = new TitleService($repositoryMock);

        $repositoryMock->expects()->create($data)->once()->andReturns($title);
        $strategyMock->expects()->setActivatable($title)->once()->andReturns($strategyMock);
        $strategyMock->expects()->activate($activationDate)->once()->andReturns($strategyMock);

        $service->create($data);
    }

    /**
     * @test
     */
    public function it_can_create_a_title_without_an_activation()
    {
        $data = [
            'name' => 'Example Title',
        ];
        $repositoryMock = $this->mock(TitleRepository::class);
        $strategyMock = $this->mock(TitleActivationStrategy::class);
        $service = new TitleService($repositoryMock);

        $repositoryMock->expects()->create($data)->once();
        $strategyMock->shouldNotReceive('employ');

        $service->create($data);
    }

    /**
     * @test
     */
    public function it_can_update_a_title_without_an_activation_start_date()
    {
        $data = [
            'name' => 'Example Name',
        ];
        $title = Title::factory()->make();
        $repositoryMock = $this->mock(TitleRepository::class);
        $service = new TitleService($repositoryMock);

        $repositoryMock->expects()->update($title, $data)->once()->andReturns($title);
        // Expect a call to not be made to employOrUpdateActivation

        $service->update($title, $data);
    }

    /**
     * @test
     */
    public function it_can_update_a_title_and_employ_if_started_at_is_filled()
    {
        $data = [
            'name' => 'Example Title',
            'activated_at' => $activationDate = Carbon::now()->toDateTimeString(),
        ];
        $title = Title::factory()->make();
        $repositoryMock = $this->mock(TitleRepository::class);
        $service = new TitleService($repositoryMock);

        $repositoryMock->expects()->update($title, $data)->once()->andReturns($title);
        // Expect a call to employOrUpdateActivation with $activationDate

        $service->update($title, $data);
    }

    /**
     * @test
     */
    public function it_can_activate_a_title_that_is_not_in_activation()
    {
        $title = Title::factory()->make();
        $activationDate = Carbon::now()->addWeek()->toDateTimeString();
        Activation::factory()->make(['activatable_id' => 1, ['started_at' => $activationDate]]);
        $repositoryMock = $this->mock(TitleRepository::class);
        $serviceMock = $this->mock(TitleActivationStrategy::class);
        $service = new TitleService($repositoryMock);

        $serviceMock->expects()->setActivatable($title)->once()->andReturns($serviceMock);
        $serviceMock->expects()->activate($activationDate)->once()->andReturns($serviceMock);

        $service->activateOrUpdateActivation($title, $activationDate);
    }

    /**
     * @test
     */
    public function it_can_update_a_title_activation_date_when_title_has_future_activation()
    {
        $title = Title::factory()->make();
        $activationDate = Carbon::now()->addWeek()->toDateTimeString();
        Activation::factory()->make(['activatable_id' => 1, ['started_at' => $activationDate]]);
        $repositoryMock = $this->mock(TitleRepository::class);
        $serviceMock = $this->mock(TitleActivationStrategy::class);
        $service = new TitleService($repositoryMock);

        $serviceMock->expects()->setActivatable($title)->once()->andReturns($serviceMock);
        $serviceMock->expects()->activate($activationDate)->once()->andReturns($serviceMock);

        $service->activateOrUpdateActivation($title, $activationDate);
    }

    /**
     * @test
     */
    public function it_can_delete_a_title()
    {
        $title = Title::factory()->make();
        $repositoryMock = $this->mock(TitleRepository::class);
        $service = new TitleService($repositoryMock);

        $repositoryMock->expects()->delete($title)->once();

        $service->delete($title);
    }

    /**
     * @test
     */
    public function it_can_restore_a_title()
    {
        $title = new Title;
        $repositoryMock = $this->mock(TitleRepository::class);
        $service = new TitleService($repositoryMock);

        $repositoryMock->expects()->restore($title)->once();

        $service->restore($title);
    }

    /**
     * @test
     */
    public function it_can_activate_a_title()
    {
        $title = new Title();
        $strategyMock = $this->mock(TitleActivationStrategy::class);
        $repositoryMock = $this->mock(TitleRepository::class);
        $service = new TitleService($repositoryMock);

        $strategyMock->expects()->setActivatable($title)->once()->andReturns($strategyMock);
        $strategyMock->expects()->activate()->once();

        $service->activate($title);
    }

    /**
     * @test
     */
    public function it_can_deactivate_a_title()
    {
        $title = new Title();
        $strategyMock = $this->mock(TitleDeactivationStrategy::class);
        $repositoryMock = $this->mock(TitleRepository::class);
        $service = new TitleService($repositoryMock);

        $strategyMock->expects()->setDeactivatable($title)->once()->andReturns($strategyMock);
        $strategyMock->expects()->deactivate()->once();

        $service->deactivate($title);
    }

    /**
     * @test
     */
    public function it_can_retire_a_title()
    {
        $title = new Title();
        $strategyMock = $this->mock(TitleRetirementStrategy::class);
        $repositoryMock = $this->mock(TitleRepository::class);
        $service = new TitleService($repositoryMock);

        $strategyMock->expects()->setRetirable($title)->once()->andReturns($strategyMock);
        $strategyMock->expects()->retire()->once();

        $service->retire($title);
    }

    /**
     * @test
     */
    public function it_can_unretire_a_title()
    {
        $title = new Title();
        $strategyMock = $this->mock(TitleUnretireStrategy::class);
        $repositoryMock = $this->mock(TitleRepository::class);
        $service = new TitleService($repositoryMock);

        $strategyMock->expects()->setUnretirable($title)->once()->andReturns($strategyMock);
        $strategyMock->expects()->unretire()->once();

        $service->unretire($title);
    }
}
