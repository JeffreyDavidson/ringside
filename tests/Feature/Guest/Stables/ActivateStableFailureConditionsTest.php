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
class ActivateStableFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cannot_activate_a_pending_introduction_stable()
    {
        $stable = StableFactory::new()->pendingIntroduction()->create();

        $response = $this->introduceRequest($stable);

        $response->assertRedirect(route('login'));
    }
}
