<?php

namespace Tests\Feature\User\Events;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\VenueFactory;
use Tests\TestCase;

/**
 * @group events
 * @group users
 */
class CreateEventFailureConditionsTest extends TestCase
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
    public function a_basic_user_cannot_view_the_form_for_creating_an_event()
    {
        $this->actAs(Role::BASIC);

        $response = $this->createRequest('events');

        $response->assertForbidden();
    }

    /** @test */
    public function a_basic_user_cannot_create_an_event()
    {
        $this->actAs(Role::BASIC);

        $response = $this->storeRequest('events', $this->validParams());

        $response->assertForbidden();
    }

    /** @test */
    public function a_guest_cannot_view_the_form_for_creating_an_event()
    {
        $response = $this->get(route('events.create'));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function a_guest_cannot_create_an_event()
    {
        $response = $this->post(route('events.store'), $this->validParams());

        $response->assertRedirect(route('login'));
    }
}
