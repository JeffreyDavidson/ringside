<?php

namespace Tests\Feature\Guest\Referees;

use Illuminate\Foundation\Testing\RefreshDatabase;
use RefereeFactory;
use Tests\TestCase;

/**
 * @group referees
 * @group guests
 * @group roster
 */
class EmployRefereeFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cannot_employ_a_pending_employment_referee()
    {
        $referee = RefereeFactory::new()->pendingEmployment()->create();

        $response = $this->employRequest($referee);

        $response->assertRedirect(route('login'));
    }
}
