<?php

namespace Tests\Feature\Http\Controllers\Events;

use App\Enums\Role;
use App\Http\Controllers\Events\EventsController;
use App\Http\Requests\Events\StoreRequest;
use App\Http\Requests\Events\UpdateRequest;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\EventRequestDataFactory;
use Tests\TestCase;

/**
 * @group events
 * @group feature-events
 */
class EventControllerTest extends TestCase
{
    use RefreshDatabase;

    private Event $event;
    private EventRequestDataFactory $factory;

    public function setUp(): void
    {
        parent::setUp();

        $this->event = Event::factory()->create();
        $this->factory = EventRequestDataFactory::new()->withEvent($this->event);
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function index_returns_a_view($administrators)
    {
        $this
            ->actAs($administrators)
            ->get(action([EventsController::class, 'index']))
            ->assertOk()
            ->assertViewIs('events.index')
            ->assertSeeLivewire('events.scheduled-events')
            ->assertSeeLivewire('events.unscheduled-events')
            ->assertSeeLivewire('events.past-events');
    }

    /**
     * @test
     */
    public function a_basic_user_cannot_view_events_index_page()
    {
        $this
            ->actAs(Role::BASIC)
            ->get(action([EventsController::class, 'index']))
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function a_guest_cannot_view_events_index_page()
    {
        $this
            ->get(action([EventsController::class, 'index']))
            ->assertRedirect(route('login'));
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function create_returns_a_view($administrators)
    {
        $this
            ->actAs($administrators)
            ->get(action([EventsController::class, 'create']))
            ->assertViewIs('events.create')
            ->assertViewHas('event', new Event);
    }

    /**
     * @test
     */
    public function a_basic_user_cannot_view_the_form_for_creating_an_event()
    {
        $this
            ->actAs(Role::BASIC)
            ->get(action([EventsController::class, 'create']))
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function a_guest_cannot_view_the_form_for_creating_an_event()
    {
        $this
            ->get(action([EventsController::class, 'create']))
            ->assertRedirect(route('login'));
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function store_creates_an_event_and_redirects($administrators)
    {
        $this
            ->actAs($administrators)
            ->from(action([EventsController::class, 'create']))
            ->post(action([EventsController::class, 'store']), EventRequestDataFactory::new()->create())
            ->assertRedirect(action([EventsController::class, 'index']));

        tap(Event::all()->last(), function ($event) {
            $this->assertEquals('Example Event Name', $event->name);
            $this->assertEquals('2021-01-01 00:00:00', $event->date);
            $this->assertEquals(1, $event->venue_id);
            $this->assertEquals('This is an event preview.', $event->preview);
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

    /**
     * @test
     * @dataProvider administrators
     */
    public function show_returns_a_view($administrators)
    {
        $this
            ->actAs($administrators)
            ->get(action([EventsController::class, 'show'], $this->event))
            ->assertViewIs('events.show')
            ->assertViewHas('event', $this->event);
    }

    /**
     * @test
     */
    public function a_basic_user_cannot_view_an_event_page()
    {
        $this
            ->actAs(Role::BASIC)
            ->get(action([EventsController::class, 'show'], $this->event))
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function a_guest_cannot_view_an_event_page()
    {
        $this
            ->get(action([EventsController::class, 'show'], $this->event))
            ->assertRedirect(route('login'));
    }

    /**
     * @test
     */
    public function an_administrator_can_view_the_form_for_editing_a_scheduled_event()
    {
        $this->event = Event::factory()->scheduled()->create();

        $this
            ->actAs(Role::ADMINISTRATOR)
            ->get(action([EventsController::class, 'edit'], $this->event))
            ->assertViewIs('events.edit')
            ->assertViewHas('event', $this->event);
    }

    /**
     * @test
     */
    public function a_basic_user_cannot_view_the_form_for_editing_an_event()
    {
        $this
            ->actAs(Role::BASIC)
            ->get(action([EventsController::class, 'edit'], $this->event))
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function a_guest_cannot_view_the_form_for_editing_an_event()
    {
        $this
            ->get(action([EventsController::class, 'edit'], $this->event))
            ->assertRedirect(route('login'));
    }

    /**
     * @test
     */
    public function a_past_event_cannot_be_edited()
    {
        $this->event = Event::factory()->past()->create();

        $this
            ->actAs(Role::ADMINISTRATOR)
            ->get(action([EventsController::class, 'edit'], $this->event))
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function an_administrator_can_update_a_scheduled_event()
    {
        $now = now();
        Carbon::setTestNow($now);

        $this->event = Event::factory()->scheduled()->create();

        $this
            ->actAs(Role::ADMINISTRATOR)
            ->from(action([EventsController::class, 'edit'], $this->event))
            ->put(action([EventsController::class, 'update'], $this->event), $this->factory->create())
            ->assertRedirect(action([EventsController::class, 'index']));

        tap($this->event->fresh(), function ($event) use ($now) {
            $this->assertEquals('Example Event Name', $event->name);
            $this->assertEquals('2021-01-01 00:00:00', $event->date);
            $this->assertEquals('This is an event preview.', $event->preview);
        });
    }

    /**
     * @test
     */
    public function a_basic_user_cannot_update_an_event()
    {
        $this
            ->actAs(Role::BASIC)
            ->from(action([EventsController::class, 'edit'], $this->event))
            ->put(action([EventsController::class, 'update'], $this->event), $this->factory->create())
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function a_guest_cannot_update_an_event()
    {
        $this
            ->from(action([EventsController::class, 'edit'], $this->event))
            ->put(action([EventsController::class, 'update'], $this->event), $this->factory->create())
            ->assertRedirect(route('login'));
    }

    /**
     * @test
     */
    public function a_past_event_cannot_be_updated()
    {
        $this->event = Event::factory()->past()->create();

        $this
            ->actAs(Role::ADMINISTRATOR)
            ->from(action([EventsController::class, 'edit'], $this->event))
            ->put(action([EventsController::class, 'update'], $this->event), $this->factory->create())
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function update_validates_using_a_form_request()
    {
        $this->assertActionUsesFormRequest(EventsController::class, 'update', UpdateRequest::class);
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function deletes_an_event_and_redirects($administrators)
    {
        $this
            ->actAs($administrators)
            ->delete(action([EventsController::class, 'destroy'], $this->event));

        $this->assertSoftDeleted($this->event);
    }

    /**
     * @test
     */
    public function a_basic_user_cannot_delete_an_event()
    {
        $this
            ->actAs(Role::BASIC)
            ->delete(action([EventsController::class, 'destroy'], $this->event))
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function a_guest_cannot_delete_an_event()
    {
        $this
            ->delete(action([EventsController::class, 'destroy'], $this->event))
            ->assertRedirect(route('login'));
    }
}
