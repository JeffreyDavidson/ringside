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
class DeleteStableFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cannot_delete_an_active_stable()
    {
        $stable = StableFactory::new()->active()->create();

        $response = $this->delete($stable);

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function a_guest_cannot_delete_a_pending_introduction_stable()
    {
        $stable = StableFactory::new()->pendingIntroduction()->create();

        $response = $this->deleteRequest($stable);

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function a_guest_cannot_delete_a_retired_stable()
    {
        $stable = StableFactory::new()->retired()->create();

        $response = $this->deleteRequest($stable);

        $response->assertRedirect(route('login'));
    }
}
