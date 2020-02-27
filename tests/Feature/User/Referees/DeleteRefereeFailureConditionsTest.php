<?php

namespace Tests\Feature\User\Referees;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\RefereeFactory;
use Tests\TestCase;

/**
 * @group referees
 * @group users
 * @group roster
 */
class DeleteRefereeFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_delete_a_bookable_referee()
    {
        $this->actAs(Role::BASIC);
        $referee = RefereeFactory::new()->bookable()->create();

        $response = $this->deleteRequest($referee);

        $response->assertForbidden();
    }

    /** @test */
    public function a_basic_user_cannot_delete_a_pending_employment_referee()
    {
        $this->actAs(Role::BASIC);
        $referee = RefereeFactory::new()->pendingEmployment()->create();

        $response = $this->deleteRequest($referee);

        $response->assertForbidden();
    }

    /** @test */
    public function a_basic_user_cannot_delete_a_suspended_referee()
    {
        $this->actAs(Role::BASIC);
        $referee = RefereeFactory::new()->suspended()->create();

        $response = $this->deleteRequest($referee);

        $response->assertForbidden();
    }

    /** @test */
    public function a_basic_user_cannot_delete_an_injured_referee()
    {
        $this->actAs(Role::BASIC);
        $referee = RefereeFactory::new()->injured()->create();

        $response = $this->deleteRequest($referee);

        $response->assertForbidden();
    }

    /** @test */
    public function a_basic_user_cannot_delete_a_retired_referee()
    {
        $this->actAs(Role::BASIC);
        $referee = RefereeFactory::new()->retired()->create();

        $response = $this->deleteRequest($referee);

        $response->assertForbidden();
    }
}
