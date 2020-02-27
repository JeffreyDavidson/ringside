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
class RetireStableFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cannot_retire_an_active_stable()
    {
        $stable = StableFactory::new()->active()->create();

        $response = $this->retireRequest($stable);

        $response->assertRedirect(route('login'));
    }
}
