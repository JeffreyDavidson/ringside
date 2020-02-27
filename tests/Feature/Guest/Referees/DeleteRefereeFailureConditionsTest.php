<?php

namespace Tests\Feature\Guest\Referees;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\RefereeFactory;
use Tests\TestCase;

/**
 * @group referees
 * @group guests
 * @group roster
 */
class DeleteRefereeFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cannot_delete_a_bookable_referee()
    {
        $referee = RefereeFactory::new()->bookable()->create();

        $response = $this->deleteRequest($referee);

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function a_guest_cannot_delete_a_pending_employment_referee()
    {
        $referee = RefereeFactory::new()->pendingEmployment()->create();

        $response = $this->deleteRequest($referee);

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function a_guest_cannot_delete_a_retired_referee()
    {
        $referee = RefereeFactory::new()->retired()->create();

        $response = $this->deleteRequest($referee);

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function a_guest_cannot_delete_a_suspended_referee()
    {
        $referee = RefereeFactory::new()->suspended()->create();

        $response = $this->deleteRequest($referee);

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function a_guest_cannot_delete_an_injured_referee()
    {
        $referee = RefereeFactory::new()->injured()->create();

        $response = $this->deleteRequest($referee);

        $response->assertRedirect(route('login'));
    }
}
