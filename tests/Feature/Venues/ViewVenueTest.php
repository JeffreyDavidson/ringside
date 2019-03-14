<?php

namespace Tests\Feature\Venues;

use Tests\TestCase;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewVenueTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_administrator_can_view_a_venue()
    {
        $this->actAs('administrator');
        $venue = factory(Venue::class)->create();

        $response = $this->get(route('venues.show', ['venue' => $venue]));

        $response->assertViewIs('venues.show');
        $this->assertTrue($response->data('venue')->is($venue));
    }
}
