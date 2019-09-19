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
class EmployManagerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cannot_employ_a_pending_employment_manager()
    {
        $manager = factory(Manager::class)->states('pending-employment')->create();

        $response = $this->employRequest($manager);

        $response->assertRedirect(route('login'));
    }
}
