<?php

namespace Tests\Feature\SuperAdmin\Referees;

use Tests\TestCase;
use App\Models\Referee;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
        $this->actAs('super-administrator');
        $referee = factory(Referee::class)->states('bookable')->create();

        $this->deleteRequest($referee);

        $this->assertSoftDeleted('referees', ['id' => $referee->id, 'first_name' => $referee->first_name, 'last_name' => $referee->last_name]);
    }

    /** @test */
    public function a_super_administrator_can_delete_a_pending_employment_referee()
    {
        $this->actAs('super-administrator');
        $referee = factory(Referee::class)->states('pending-employment')->create();

        $this->deleteRequest($referee);

        $this->assertSoftDeleted('referees', ['id' => $referee->id, 'first_name' => $referee->first_name, 'last_name' => $referee->last_name]);
    }

    /** @test */
    public function a_super_administrator_can_delete_a_retired_referee()
    {
        $this->actAs('super-administrator');
        $referee = factory(Referee::class)->states('retired')->create();

        $this->deleteRequest($referee);

        $this->assertSoftDeleted('referees', ['id' => $referee->id, 'first_name' => $referee->first_name, 'last_name' => $referee->last_name]);
    }

    /** @test */
    public function a_super_administrator_can_delete_a_suspended_referee()
    {
        $this->actAs('super-administrator');
        $referee = factory(Referee::class)->states('suspended')->create();

        $this->deleteRequest($referee);

        $this->assertSoftDeleted('referees', ['id' => $referee->id, 'first_name' => $referee->first_name, 'last_name' => $referee->last_name]);
    }

    /** @test */
    public function a_super_administrator_can_delete_an_injured_referee()
    {
        $this->actAs('super-administrator');
        $referee = factory(Referee::class)->states('injured')->create();

        $this->deleteRequest($referee);

        $this->assertSoftDeleted('referees', ['id' => $referee->id, 'first_name' => $referee->first_name, 'last_name' => $referee->last_name]);
    }
}
