<?php

namespace Tests\Feature\Events;

use App\Enums\Role;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\EventFactory;
use Tests\TestCase;

/**
 * @group events
 * @group users
 */
class RestoreEventFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_restore_a_deleted_event()
    {
        $this->actAs(Role::BASIC);
        $event = EventFactory::new()->softDeleted()->create();

        $response = $this->put(route('events.restore', $event));

        $response->assertForbidden();
    }

    /** @test */
    public function a_guest_cannot_restore_a_event()
    {
        $event = factory(Event::class)->create(['deleted_at' => today()->subDays(3)->toDateTimeString()]);

        $response = $this->put(route('events.restore', $event));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function a_scheduled_event_cannot_be_restored()
    {
        $this->actAs('administrator');
        $event = factory(Event::class)->states('scheduled')->create();

        $response = $this->put(route('events.restore', $event));

        $response->assertNotFound();
    }

    /** @test */
    public function a_past_event_cannot_be_restored()
    {
        $this->actAs('administrator');
        $event = factory(Event::class)->states('past')->create();

        $response = $this->put(route('events.restore', $event));

        $response->assertNotFound();
    }
}
