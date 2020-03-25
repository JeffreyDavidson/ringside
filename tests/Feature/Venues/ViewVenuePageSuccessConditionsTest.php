<?php

namespace Tests\Feature\Venues;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\VenueFactory;
use Tests\TestCase;

/**
 * @group venues
 */
class ViewVenuePageSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_administrator_can_view_a_venue()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $venue = VenueFactory::new()->create();

        $response = $this->showRequest($venue);

        $response->assertViewIs('venues.show');
        $this->assertTrue($response->data('venue')->is($venue));
    }
}
