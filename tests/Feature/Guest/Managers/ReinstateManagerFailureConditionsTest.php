<?php

namespace Tests\Feature\Guest\Managers;

use App\Models\Manager;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group managers
 * @group guests
 * @group roster
 */
class ReinstateManagerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cannot_reinstate_a_suspended_manager()
    {
        $manager = factory(Manager::class)->states('suspended')->create();

        $response = $this->reinstateRequest($manager);

        $response->assertRedirect(route('login'));
    }
}
