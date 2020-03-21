<?php

namespace Tests\Feature\User\Events;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\EventFactory;
use Tests\Factories\VenueFactory;
use Tests\TestCase;

/**
 * @group events
 * @group users
 */
class UpdateEventFailureConditionsTest extends TestCase
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
    public function a_basic_user_cannot_view_the_form_for_editing_an_event()
    {
        $this->actAs(Role::BASIC);
        $event = EventFactory::new()->create();

        $response = $this->editRequest($event);

        $response->assertForbidden();
    }

    /** @test */
    public function a_basic_user_cannot_update_an_event()
    {
        $this->actAs(Role::BASIC);
        $event = EventFactory::new()->create();

        $response = $this->updateRequest($event, $this->validParams());

        $response->assertForbidden();
    }

    /** @test */
    public function a_guest_cannot_view_the_form_for_editing_an_event()
    {
        $event = factory(Event::class)->create();

        $response = $this->get(route('events.edit', $event));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function a_guest_cannot_update_an_event()
    {
        $event = factory(Event::class)->create();

        $response = $this->patch(route('events.update', $event), $this->validParams());

        $response->assertRedirect(route('login'));
    }
}
