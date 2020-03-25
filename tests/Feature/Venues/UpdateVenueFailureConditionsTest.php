<?php

namespace Tests\Feature\Venues;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\VenueFactory;
use Tests\TestCase;

/**
 * @group venues
 */
class UpdateVenueFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Valid parameters for request.
     *
     * @param  array $overrides
     * @return array
     */
    private function validParams($overrides = [])
    {
        return array_replace([
            'name' => 'Example Venue',
            'address1' => '123 Main Street',
            'address2' => 'Suite 100',
            'city' => 'Laraville',
            'state' => 'New York',
            'zip' => '12345',
        ], $overrides);
    }

    /** @test */
    public function a_basic_user_cannot_view_the_form_for_editing_a_venue()
    {
        $this->actAs(Role::BASIC);
        $venue = VenueFactory::new()->create();

        $response = $this->editRequest($venue);

        $response->assertForbidden();
    }

    /** @test */
    public function a_basic_user_cannot_update_a_venue()
    {
        $this->actAs(Role::BASIC);
        $venue = VenueFactory::new()->create();

        $response = $this->updateRequest($venue, $this->validParams());

        $response->assertForbidden();
    }

    /** @test */
    public function a_guest_cannot_view_the_form_for_editing_a_venue()
    {
        $venue = factory(Venue::class)->create();

        $response = $this->editRequest($venue);

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function a_guest_cannot_update_a_venue()
    {
        $venue = factory(Venue::class)->create();

        $response = $this->updateRequest($venue, $this->validParams());

        $response->assertRedirect(route('login'));
    }
}
