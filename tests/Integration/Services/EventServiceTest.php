<?php

namespace Tests\Integration\Services;

use App\Models\Event;
use App\Repositories\EventRepository;
use App\Services\EventService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group events
 * @group services
 */
class EventServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_can_create_a_event()
    {
        $data = [
            'name' => 'Example Event',
            'date' => $date = Carbon::now()->toDateTimeString(),
            'venue_id' => 1,
            'preview' => 'Example preview to be seen.',
        ];
        $event = Event::factory()->make(['name' => 'Example Event', 'date' => $date, 'venue_id' => 1, 'preview' => 'Example preview to be seen.']);
        $repositoryMock = $this->mock(EventRepository::class);
        $service = new EventService($repositoryMock);

        $repositoryMock->expects()->create($data)->once()->andReturns($event);

        $service->create($data);
    }

    /**
     * @test
     */
    public function it_can_update_a_event()
    {
        $data = [
            'name' => 'Example Event',
            'date' => $date = Carbon::now()->toDateTimeString(),
            'venue_id' => 1,
            'preview' => 'Example preview to be seen.',
        ];
        $event = Event::factory()->make(['name' => 'Example Event', 'date' => $date, 'venue_id' => 1, 'preview' => 'Example preview to be seen.']);
        $repositoryMock = $this->mock(EventRepository::class);
        $service = new EventService($repositoryMock);

        $repositoryMock->expects()->update($event, $data)->once()->andReturns($event);

        $service->update($event, $data);
    }

    /**
     * @test
     */
    public function it_can_delete_a_event()
    {
        $event = Event::factory()->make();
        $repositoryMock = $this->mock(EventRepository::class);
        $service = new EventService($repositoryMock);

        $repositoryMock->expects()->delete($event)->once();

        $service->delete($event);
    }

    /**
     * @test
     */
    public function it_can_restore_a_event()
    {
        $event = new Event;
        $repositoryMock = $this->mock(EventRepository::class);
        $service = new EventService($repositoryMock);

        $repositoryMock->expects()->restore($event)->once();

        $service->restore($event);
    }
}
