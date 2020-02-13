<?php

namespace Tests\Feature\Admin\Referees;

use App\Enums\Role;
use RefereeFactory;
use Tests\TestCase;
use App\Models\Referee;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group referees
 * @group admins
 * @group roster
 */
class DeleteRefereeSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_administrator_can_delete_a_bookable_referee()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $referee = RefereeFactory::new()->bookable()->create();

        $this->deleteRequest($referee);

        $this->assertSoftDeleted('referees', [
            'id' => $referee->id,
            'first_name' => $referee->first_name,
            'last_name' => $referee->last_name
        ]);
    }

    /** @test */
    public function an_administrator_can_delete_a_pending_employment_referee()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $referee = RefereeFactory::new()->pendingEmployment()->create();

        $this->deleteRequest($referee);

        $this->assertSoftDeleted('referees', [
            'id' => $referee->id,
            'first_name' => $referee->first_name,
            'last_name' => $referee->last_name
        ]);
    }

    /** @test */
    public function an_administrator_can_delete_a_retired_referee()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $referee = RefereeFactory::new()->retired()->create();

        $this->deleteRequest($referee);

        $this->assertSoftDeleted('referees', [
            'id' => $referee->id,
            'first_name' => $referee->first_name,
            'last_name' => $referee->last_name
        ]);
    }

    /** @test */
    public function an_administrator_can_delete_a_suspended_referee()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $referee = RefereeFactory::new()->suspended()->create();

        $this->deleteRequest($referee);

        $this->assertSoftDeleted('referees', [
            'id' => $referee->id,
            'first_name' => $referee->first_name,
            'last_name' => $referee->last_name
        ]);
    }

    /** @test */
    public function an_administrator_can_delete_an_injured_referee()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $referee = RefereeFactory::new()->injured()->create();

        $this->deleteRequest($referee);

        $this->assertSoftDeleted('referees', [
            'id' => $referee->id,
            'first_name' => $referee->first_name,
            'last_name' => $referee->last_name
        ]);
    }
}
