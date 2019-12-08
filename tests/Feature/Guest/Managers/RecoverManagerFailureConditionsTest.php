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
class RecoverManagerFailureConditionsTest extends TestCase
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
