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
class HealManagerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cannot_recover_an_injured_manager()
    {
        $manager = factory(Manager::class)->states('injured')->create();

        $response = $this->markAsHealedRequest($manager);

        $response->assertRedirect(route('login'));
    }
}
