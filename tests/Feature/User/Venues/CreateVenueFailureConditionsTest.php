<?php

namespace Tests\Feature\User\Venues;

use Tests\TestCase;

/**
 * @group venues
 * @group users
 */
class CreateVenueFailureConditionsTest extends TestCase
{
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
    public function a_basic_user_cannot_view_the_form_for_creating_a_venue()
    {
        $this->actAs('basic-user');

        $response = $this->createRequest('venue');

        $response->assertForbidden();
    }

    /** @test */
    public function a_basic_user_cannot_create_a_venue()
    {
        $this->actAs('basic-user');

        $response = $this->storeRequest('venue', $this->validParams());

        $response->assertForbidden();
    }
}
