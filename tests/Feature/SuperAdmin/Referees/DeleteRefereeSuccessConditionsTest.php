<?php

namespace Tests\Feature\SuperAdmin\Referees;

use Tests\TestCase;
use App\Models\Referee;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group referees
 * @group superadmins
 */
class DeleteRefereeSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_super_administrator_can_delete_a_bookable_referee()
    {
        $this->actAs('super-administrator');
        $referee = factory(Referee::class)->states('bookable')->create();

        $this->delete(route('referees.destroy', $referee));

        $this->assertSoftDeleted('referees', ['id' => $referee->id, 'first_name' => $referee->first_name, 'last_name' => $referee->last_name]);
    }

    /** @test */
    public function a_super_administrator_can_delete_a_pending_introduction_referee()
    {
        $this->actAs('super-administrator');
        $referee = factory(Referee::class)->states('pending-introduction')->create();

        $this->delete(route('referees.destroy', $referee));

        $this->assertSoftDeleted('referees', ['id' => $referee->id, 'first_name' => $referee->first_name, 'last_name' => $referee->last_name]);
    }

    /** @test */
    public function a_super_administrator_can_delete_a_retired_referee()
    {
        $this->actAs('super-administrator');
        $referee = factory(Referee::class)->states('retired')->create();

        $this->delete(route('referees.destroy', $referee));

        $this->assertSoftDeleted('referees', ['id' => $referee->id, 'first_name' => $referee->first_name, 'last_name' => $referee->last_name]);
    }

    /** @test */
    public function a_super_administrator_can_delete_a_suspended_referee()
    {
        $this->actAs('super-administrator');
        $referee = factory(Referee::class)->states('suspended')->create();

        $this->delete(route('referees.destroy', $referee));

        $this->assertSoftDeleted('referees', ['id' => $referee->id, 'first_name' => $referee->first_name, 'last_name' => $referee->last_name]);
    }

    /** @test */
    public function a_super_administrator_can_delete_an_injured_referee()
    {
        $this->actAs('super-administrator');
        $referee = factory(Referee::class)->states('injured')->create();

        $this->delete(route('referees.destroy', $referee));

        $this->assertSoftDeleted('referees', ['id' => $referee->id, 'first_name' => $referee->first_name, 'last_name' => $referee->last_name]);
    }
}
