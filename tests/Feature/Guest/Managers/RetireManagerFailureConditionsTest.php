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
class RetireManagerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cannot_retire_an_available_manager()
    {
        $manager = factory(Manager::class)->states('available')->create();

        $response = $this->retireRequest($manager);

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function a_guest_cannot_retire_a_suspended_manager()
    {
        $manager = factory(Manager::class)->states('suspended')->create();

        $response = $this->retireRequest($manager);

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function a_guest_cannot_retire_an_injured_manager()
    {
        $manager = factory(Manager::class)->states('injured')->create();

        $response = $this->retireRequest($manager);

        $response->assertRedirect(route('login'));
    }
}
