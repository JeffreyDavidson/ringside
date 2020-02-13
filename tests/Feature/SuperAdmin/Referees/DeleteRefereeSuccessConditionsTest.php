<?php

namespace Tests\Feature\SuperAdmin\Referees;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use RefereeFactory;
use Tests\TestCase;

/**
 * @group referees
 * @group superadmins
 * @group roster
 */
class DeleteRefereeSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_super_administrator_can_delete_a_bookable_referee()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $referee = RefereeFactory::new()->bookable()->create();

        $this->deleteRequest($referee);

        $this->assertSoftDeleted($referee);
    }

    /** @test */
    public function a_super_administrator_can_delete_a_pending_employment_referee()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $referee = RefereeFactory::new()->pendingEmployment()->create();

        $this->deleteRequest($referee);

        $this->assertSoftDeleted($referee);
    }

    /** @test */
    public function a_super_administrator_can_delete_a_retired_referee()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $referee = RefereeFactory::new()->retired()->create();

        $this->deleteRequest($referee);

        $this->assertSoftDeleted($referee);
    }

    /** @test */
    public function a_super_administrator_can_delete_a_suspended_referee()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $referee = RefereeFactory::new()->suspended()->create();

        $this->deleteRequest($referee);

        $this->assertSoftDeleted($referee);
    }

    /** @test */
    public function a_super_administrator_can_delete_an_injured_referee()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $referee = RefereeFactory::new()->injured()->create();

        $this->deleteRequest($referee);

        $this->assertSoftDeleted($referee);
    }
}
