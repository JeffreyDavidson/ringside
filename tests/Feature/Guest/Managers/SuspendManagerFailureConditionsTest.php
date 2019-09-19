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
class SuspendManagerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cannot_suspend_a_bookable_manager()
    {
        $manager = factory(Manager::class)->states('bookable')->create();

        $response = $this->suspendRequest($manager);

        $response->assertRedirect(route('login'));
    }
}
