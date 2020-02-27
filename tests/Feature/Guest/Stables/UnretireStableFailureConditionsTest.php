<?php

namespace Tests\Feature\Guest\Stables;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\StableFactory;
use Tests\TestCase;

/**
 * @group stables
 * @group guests
 * @group roster
 */
class UnretireStableFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cannot_unretire_a_retired_stable()
    {
        $stable = StableFactory::new()->retired()->create();

        $response = $this->retireRequest($stable);

        $response->assertRedirect(route('login'));
    }
}
