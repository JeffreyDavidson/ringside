<?php

namespace Tests\Feature\Events;

use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group events
 * @group admins
 */
class DeleteEventSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_administrator_can_delete_a_scheduled_event()
    {
        $this->actAs('administrator');
        $event = factory(Event::class)->states('scheduled')->create();

        $this->delete(route('events.destroy', $event));

        $this->assertSoftDeleted('events', ['name' => $event->name]);
    }

    /** @test */
    public function an_administrator_can_delete_a_past_event()
    {
        $this->actAs('administrator');
        $event = factory(Event::class)->states('past')->create();

        $this->delete(route('events.destroy', $event));

        $this->assertSoftDeleted('events', ['name' => $event->name]);
    }
}
