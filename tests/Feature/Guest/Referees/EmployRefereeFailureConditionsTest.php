<?php

namespace Tests\Feature\Guest\Referees;

use Tests\TestCase;
use App\Models\Referee;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
        $referee = factory(Referee::class)->states('pending-employment')->create();

        $response = $this->employRequest($referee);

        $response->assertRedirect(route('login'));
    }
}
