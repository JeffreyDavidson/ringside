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
class ClearFromInjuryManagerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cannot_clear_manager_from_injury()
    {
        $manager = factory(Manager::class)->states('injured')->create();

        $response = $this->clearInjuryRequest($manager);

        $response->assertRedirect(route('login'));
    }
}
