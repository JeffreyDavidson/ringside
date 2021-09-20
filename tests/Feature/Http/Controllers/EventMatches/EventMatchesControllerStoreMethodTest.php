<?php

namespace Tests\Feature\Http\Controllers\Events;

use App\Enums\Role;
use App\Http\Controllers\EventMatches\EventMatchesController;
use App\Http\Controllers\Events\EventsController;
use App\Http\Requests\Events\StoreRequest;
use App\Models\Event;
use App\Models\Venue;
use Database\Seeders\MatchTypesTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\EventMatchesRequestDataFactory;
use Tests\Factories\EventRequestDataFactory;
use Tests\TestCase;

/**
 * @group events
 * @group feature-events
 */
class EventMatchesControllerStoreMethodTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed(MatchTypesTableSeeder::class);
    }

    /**
     * @test
     */
    public function store_creates_an_event_and_redirects()
    {
        $this->withoutExceptionHandling();
        $event = Event::factory()->create();

        $this
            ->actAs(Role::ADMINISTRATOR)
            ->from(action([EventMatchesController::class, 'create'], $event))
            ->post(
                action([EventMatchesController::class, 'store'], $event),
                EventMatchesRequestDataFactory::new()->create([
                    'matches' => [],
                ])
            )
            ->assertRedirect(action([EventMatchesController::class, 'index'], $event));

        $this->assertCount(0, $event->matches);
    }

    /**
     * @test
     */
    public function store_creates_an_event_with_a_venue_and_redirects()
    {
        $venue = Venue::factory()->create();

        $this
            ->actAs(Role::ADMINISTRATOR)
            ->from(action([EventsController::class, 'create']))
            ->post(action([EventsController::class, 'store']), EventRequestDataFactory::new()->create([
                'venue_id' => $venue->id,
            ]));

        tap(Event::first(), function ($event) use ($venue) {
            $this->assertTrue($event->venue->is($venue));
        });
    }

    /**
     * @test
     */
    public function store_creates_an_event_with_a_date_and_redirects()
    {
        $this
            ->actAs(Role::ADMINISTRATOR)
            ->from(action([EventsController::class, 'create']))
            ->post(action([EventsController::class, 'store']), EventRequestDataFactory::new()->create([
                'date' => '2021-01-01 00:00:00',
            ]));

        tap(Event::first(), function ($event) {
            $this->assertEquals('2021-01-01 00:00:00', $event->date);
        });
    }

    /**
     * @test
     */
    public function store_creates_an_event_with_a_preview_and_redirects()
    {
        $this
            ->actAs(Role::ADMINISTRATOR)
            ->from(action([EventsController::class, 'create']))
            ->post(action([EventsController::class, 'store']), EventRequestDataFactory::new()->create([
                'preview' => 'This is a general event preview.',
            ]));

        tap(Event::first(), function ($event) {
            $this->assertEquals('This is a general event preview.', $event->preview);
        });
    }

    /**
     * @test
     */
    public function store_creates_an_event_with_matches_and_redirects()
    {
        $this
            ->actAs(Role::ADMINISTRATOR)
            ->from(action([EventsController::class, 'create']))
            ->post(action([EventsController::class, 'store']), EventRequestDataFactory::new()->create([
                'matches' => [
                    0 => [
                        'match_type_id' => 1,
                        'title_id' => 0,
                        'referee_id' => 1,
                        'competitors' => [1, 2],
                    ],
                ],
            ]));

        tap(Event::first(), function ($event) {
            $this->assertEquals('This is a general event preview.', $event->preview);
        });
    }

    /**
     * @test
     */
    public function a_basic_user_cannot_create_an_event()
    {
        $this
            ->actAs(Role::BASIC)
            ->from(action([EventsController::class, 'create']))
            ->post(action([EventsController::class, 'store']), EventRequestDataFactory::new()->create())
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function a_guest_cannot_create_a_event()
    {
        $this
            ->from(action([EventsController::class, 'create']))
            ->post(action([EventsController::class, 'store']), EventRequestDataFactory::new()->create())
            ->assertRedirect(route('login'));
    }

    /**
     * @test
     */
    public function store_validates_using_a_form_request()
    {
        $this->assertActionUsesFormRequest(EventsController::class, 'store', StoreRequest::class);
    }
}
