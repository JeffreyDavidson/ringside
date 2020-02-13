<?php

namespace Tests\Feature\User\Referees;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use RefereeFactory;
use Tests\TestCase;

/**
 * @group referees
 * @group users
 * @group roster
 */
class EmployRefereeFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_employ_a_pending_employment_referee()
    {
        $this->actAs(Role::BASIC);
        $referee = RefereeFactory::new()->pendingEmployment()->create();

        $response = $this->employRequest($referee);

        $response->assertForbidden();
    }
}
