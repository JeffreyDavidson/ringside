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
class DeleteManagerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cannot_delete_a_bookable_manager()
    {
        $manager = factory(Manager::class)->states('bookable')->create();

        $response = $this->deleteRequest($manager);

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function a_guest_cannot_delete_a_retired_manager()
    {
        $manager = factory(Manager::class)->states('retired')->create();

        $response = $this->deleteRequest($manager);

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function a_guest_cannot_delete_a_suspended_manager()
    {
        $manager = factory(Manager::class)->states('suspended')->create();

        $response = $this->deleteRequest($manager);

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function a_guest_cannot_delete_a_pending_employment_manager()
    {
        $manager = factory(Manager::class)->states('pending-employment')->create();

        $response = $this->deleteRequest($manager);

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function a_guest_cannot_delete_an_injured_manager()
    {
        $manager = factory(Manager::class)->states('injured')->create();

        $response = $this->deleteRequest($manager);

        $response->assertRedirect(route('login'));
    }
}
