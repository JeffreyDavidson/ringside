<?php

namespace Tests\Unit\Services;

use App\Models\Title;
use App\Repositories\TitleRepository;
use App\Services\TitleService;
use Tests\TestCase;

/**
 * @group titles
 * @group services
 */
class TitleServiceTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_create_a_title_without_an_activation()
    {
        $repositoryMock = $this->mock(TitleRepository::class);
        $service = new TitleService($repositoryMock);

        $repositoryMock->expects()->create(\Mockery::type('array'))->once();

        $service->create([]);
    }

    /**
     * @test
     */
    public function it_can_create_a_title_with_an_activation()
    {
        $data = [];
        $titleMock = $this->mock(Title::class);
        $repositoryMock = $this->mock(TitleRepository::class);
        $service = new TitleService($repositoryMock);

        $repositoryMock->expects()->create(\Mockery::type('array'))->once()->andReturns($titleMock);
        $repositoryMock->expects()->activate($titleMock, \Mockery::type('string'))->once()->andReturns($titleMock);

        $service->create(array_merge(['activated_at' => now()->toDateTimeString()], $data));
    }

    /**
     * @test
     */
    public function it_can_update_a_title_without_an_activation_start_date()
    {
        $data = [];
        $titleMock = $this->mock(Title::class);
        $repositoryMock = $this->mock(TitleRepository::class);
        $service = new TitleService($repositoryMock);

        $repositoryMock->expects()->update($titleMock, $data)->once()->andReturns($titleMock);

        $service->update($titleMock, $data);
    }

    /**
     * @test
     */
    public function it_can_update_a_title_and_employ_if_started_at_is_filled()
    {
        $data = [];
        $titleMock = $this->mock(Title::class);
        $repositoryMock = $this->mock(TitleRepository::class);
        $service = new TitleService($repositoryMock);

        $repositoryMock->expects()->update($titleMock, $data)->once()->andReturns($titleMock);

        $service->update($titleMock, $data);
    }

    /**
     * @test
     */
    public function it_can_activate_a_title_that_is_not_in_activation()
    {
        $titleMock = $this->mock(Title::class);
        $repositoryMock = $this->mock(TitleRepository::class);
        $service = new TitleService($repositoryMock);

        $service->activateOrUpdateActivation($titleMock, now()->toDateTimeString());
    }

    /**
     * @test
     */
    public function it_can_update_a_title_activation_date_when_title_has_future_activation()
    {
        $titleMock = $this->mock(Title::class);
        $repositoryMock = $this->mock(TitleRepository::class);
        $service = new TitleService($repositoryMock);

        $service->activateOrUpdateActivation($titleMock, now()->toDateTImeString());
    }

    /**
     * @test
     */
    public function it_can_delete_a_title()
    {
        $titleMock = $this->mock(Title::class);
        $repositoryMock = $this->mock(TitleRepository::class);
        $service = new TitleService($repositoryMock);

        $repositoryMock->expects()->delete($titleMock)->once();

        $service->delete($titleMock);
    }

    /**
     * @test
     */
    public function it_can_restore_a_title()
    {
        $titleMock = $this->mock(Title::class);
        $repositoryMock = $this->mock(TitleRepository::class);
        $service = new TitleService($repositoryMock);

        $repositoryMock->expects()->restore($titleMock)->once();

        $service->restore($titleMock);
    }
}
