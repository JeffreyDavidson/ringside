<?php

namespace Tests\Feature\Referees;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\RefereeFactory;
use Tests\TestCase;

/**
 * @group referees
 * @group roster
 */
class DeleteRefereeSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @dataProvider adminRoles
     */
    public function administrators_can_delete_a_bookable_referee($adminRoles)
    {
        $this->actAs($adminRoles);
        $referee = RefereeFactory::new()->bookable()->create();

        $this->deleteRequest($referee);

        $this->assertSoftDeleted('referees', [
            'id' => $referee->id,
            'first_name' => $referee->first_name,
            'last_name' => $referee->last_name,
        ]);
    }

    /**
     * @test
     * @dataProvider adminRoles
     */
    public function administrators_can_delete_a_pending_employment_referee($adminRoles)
    {
        $this->actAs($adminRoles);
        $referee = RefereeFactory::new()->pendingEmployment()->create();

        $this->deleteRequest($referee);

        $this->assertSoftDeleted('referees', [
            'id' => $referee->id,
            'first_name' => $referee->first_name,
            'last_name' => $referee->last_name,
        ]);
    }

    /**
     * @test
     * @dataProvider adminRoles
     */
    public function administrators_can_delete_a_retired_referee($adminRoles)
    {
        $this->actAs($adminRoles);
        $referee = RefereeFactory::new()->retired()->create();

        $this->deleteRequest($referee);

        $this->assertSoftDeleted('referees', [
            'id' => $referee->id,
            'first_name' => $referee->first_name,
            'last_name' => $referee->last_name,
        ]);
    }

    /**
     * @test
     * @dataProvider adminRoles
     */
    public function administrators_can_delete_a_suspended_referee($adminRoles)
    {
        $this->actAs($adminRoles);
        $referee = RefereeFactory::new()->suspended()->create();

        $this->deleteRequest($referee);

        $this->assertSoftDeleted('referees', [
            'id' => $referee->id,
            'first_name' => $referee->first_name,
            'last_name' => $referee->last_name,
        ]);
    }

    /**
     * @test
     * @dataProvider adminRoles
     */
    public function administrators_can_delete_an_injured_referee($adminRoles)
    {
        $this->actAs($adminRoles);
        $referee = RefereeFactory::new()->injured()->create();

        $this->deleteRequest($referee);

        $this->assertSoftDeleted('referees', [
            'id' => $referee->id,
            'first_name' => $referee->first_name,
            'last_name' => $referee->last_name,
        ]);
    }

    public function adminRoles()
    {
        return [
            [Role::ADMINISTRATOR],
            [Role::SUPER_ADMINISTRATOR],
        ];
    }
}
