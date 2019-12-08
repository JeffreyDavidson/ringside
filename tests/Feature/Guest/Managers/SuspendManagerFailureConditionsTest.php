<?php

namespace Tests\Feature\Guest\Managers;

use Tests\TestCase;
use App\Models\Manager;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group managers
 * @group guests
 * @group roster
 */
class SuspendManagerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cannot_suspend_an_available_manager()
    {
        $manager = factory(Manager::class)->states('available')->create();

        $response = $this->suspendRequest($manager);

        $response->assertRedirect(route('login'));
    }
}
