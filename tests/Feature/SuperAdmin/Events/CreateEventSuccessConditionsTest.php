<?php

namespace Tests\Feature\SuperAdmin\Events;

use App\Enums\Role;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\VenueFactory;
use Tests\TestCase;

/**
 * @group events
 * @group superadmins
 */
class CreateEventSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Valid parameters for request.
     *
     * @param  array  $overrides
     * @return array
     */
    private function validParams($overrides = [])
    {
        return array_replace([
            'name' => 'Example Event Name',
            'date' => now()->toDateTimeString(),
            'venue_id' => VenueFactory::new()->create()->id,
            'preview' => 'This is an event preview.',
        ], $overrides);
    }

    /** @test */
    public function a_super_administrator_can_view_the_form_for_creating_an_event()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);

        $response = $this->createRequest('events');

        $response->assertViewIs('events.create');
        $response->assertViewHas('event', new Event);
    }

    /** @test */
    public function a_super_administrator_can_create_an_event()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);

        $response = $this->storeRequest('events', $this->validParams());

        $response->assertRedirect(route('events.index'));
        tap(Event::first(), function ($event) {
            $this->assertEquals('Example Event Name', $event->name);
            $this->assertEquals(now()->toDateTimeString(), $event->date);
            $this->assertEquals(1, $event->venue_id);
            $this->assertEquals('This is an event preview.', $event->preview);
        });
    }
}
