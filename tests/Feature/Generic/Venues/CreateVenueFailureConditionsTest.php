<?php

namespace Tests\Feature\Generic\Venues;

use App\Enums\Role;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group venues
 * @group generics
 */
class CreateVenueFailureConditionsTest extends TestCase
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
    public function a_venue_name_is_required()
    {
        $this->actAs(Role::ADMINISTRATOR);

        $response = $this->storeRequest('venue', $this->validParams(['name' => null]));

        $response->assertStatus(302);
        $response->assertRedirect(route('venues.create'));
        $response->assertSessionHasErrors('name');
        $this->assertEquals(0, Venue::count());
    }

    /** @test */
    public function a_venue_name_must_be_a_string()
    {
        $this->actAs(Role::ADMINISTRATOR);

        $response = $this->storeRequest('venue', $this->validParams(['name' => ['not-a-string']]));

        $response->assertStatus(302);
        $response->assertRedirect(route('venues.create'));
        $response->assertSessionHasErrors('name');
        $this->assertEquals(0, Venue::count());
    }

    /** @test */
    public function a_venue_address_is_required()
    {
        $this->actAs(Role::ADMINISTRATOR);

        $response = $this->storeRequest('venue', $this->validParams(['address1' => null]));

        $response->assertStatus(302);
        $response->assertRedirect(route('venues.create'));
        $response->assertSessionHasErrors('address1');
        $this->assertEquals(0, Venue::count());
    }

    /** @test */
    public function a_venue_address_must_be_a_string()
    {
        $this->actAs(Role::ADMINISTRATOR);

        $response = $this->storeRequest('venue', $this->validParams(['address1' => ['not-a-string']]));

        $response->assertStatus(302);
        $response->assertRedirect(route('venues.create'));
        $response->assertSessionHasErrors('address1');
        $this->assertEquals(0, Venue::count());
    }

    /** @test */
    public function a_venue_city_is_required()
    {
        $this->actAs(Role::ADMINISTRATOR);

        $response = $this->storeRequest('venue', $this->validParams(['city' => null]));

        $response->assertStatus(302);
        $response->assertRedirect(route('venues.create'));
        $response->assertSessionHasErrors('city');
        $this->assertEquals(0, Venue::count());
    }

    /** @test */
    public function a_venue_city_must_be_a_string()
    {
        $this->actAs(Role::ADMINISTRATOR);

        $response = $this->storeRequest('venue', $this->validParams(['city' => ['not-a-string']]));

        $response->assertStatus(302);
        $response->assertRedirect(route('venues.create'));
        $response->assertSessionHasErrors('city');
        $this->assertEquals(0, Venue::count());
    }

    /** @test */
    public function a_venue_state_is_required()
    {
        $this->actAs(Role::ADMINISTRATOR);

        $response = $this->storeRequest('venue', $this->validParams(['state' => null]));

        $response->assertStatus(302);
        $response->assertRedirect(route('venues.create'));
        $response->assertSessionHasErrors('state');
        $this->assertEquals(0, Venue::count());
    }

    /** @test */
    public function a_venue_state_must_be_a_string()
    {
        $this->actAs(Role::ADMINISTRATOR);

        $response = $this->storeRequest('venue', $this->validParams(['state' => ['not-a-string']]));

        $response->assertStatus(302);
        $response->assertRedirect(route('venues.create'));
        $response->assertSessionHasErrors('state');
        $this->assertEquals(0, Venue::count());
    }

    /** @test */
    public function a_venue_zip_is_required()
    {
        $this->actAs(Role::ADMINISTRATOR);

        $response = $this->storeRequest('venue', $this->validParams(['zip' => null]));

        $response->assertStatus(302);
        $response->assertRedirect(route('venues.create'));
        $response->assertSessionHasErrors('zip');
        $this->assertEquals(0, Venue::count());
    }

    /** @test */
    public function a_venue_zip_must_be_an_integer()
    {
        $this->actAs(Role::ADMINISTRATOR);

        $response = $this->storeRequest('venue', $this->validParams(['zip' => 'not-an-integer']));

        $response->assertStatus(302);
        $response->assertRedirect(route('venues.create'));
        $response->assertSessionHasErrors('zip');
        $this->assertEquals(0, Venue::count());
    }

    /** @test */
    public function a_venue_zip_must_be_five_digits_long()
    {
        $this->actAs(Role::ADMINISTRATOR);

        $response = $this->storeRequest('venue', $this->validParams(['zip' => '123456']));

        $response->assertStatus(302);
        $response->assertRedirect(route('venues.create'));
        $response->assertSessionHasErrors('zip');
        $this->assertEquals(0, Venue::count());
    }
}
