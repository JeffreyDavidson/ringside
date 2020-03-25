<?php

namespace Tests\Feature\Venues;

use App\Enums\Role;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group venues
 */
class CreateVenueSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Valid Parameters for request.
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
    public function an_administrator_can_view_the_form_for_creating_a_venue()
    {
        $this->actAs(Role::ADMINISTRATOR);

        $response = $this->createRequest('venues');

        $response->assertViewIs('venues.create');
    }

    /** @test */
    public function an_administrator_can_create_a_venue()
    {
        $this->actAs(Role::ADMINISTRATOR);

        $response = $this->storeRequest('venues', $this->validParams());

        $response->assertRedirect(route('venues.index'));
        tap(Venue::first(), function ($venue) {
            $this->assertEquals('Example Venue', $venue->name);
            $this->assertEquals('123 Main Street', $venue->address1);
            $this->assertEquals('Suite 100', $venue->address2);
            $this->assertEquals('Laraville', $venue->city);
            $this->assertEquals('New York', $venue->state);
            $this->assertEquals(12345, $venue->zip);
        });
    }

    /** @test */
    public function a_venue_address2_is_optional()
    {
        $this->actAs(Role::ADMINISTRATOR);

        $response = $this->storeRequest('venues', $this->validParams(['address2' => null]));

        $response->assertSessionHasNoErrors();
    }
}
