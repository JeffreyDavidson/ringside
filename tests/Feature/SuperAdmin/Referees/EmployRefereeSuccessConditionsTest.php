<?php

namespace Tests\Feature\SuperAdmin\Referees;

use Tests\TestCase;
use App\Models\Referee;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group referees
 * @group superadmins
 */
class EmployRefereeSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_super_administrator_can_employ_a_pending_introduction_referee()
    {
        $this->actAs('super-administrator');
        $referee = factory(Referee::class)->states('pending-introduction')->create();

        $response = $this->put(route('referees.employ', $referee));

        $response->assertRedirect(route('referees.index'));
        tap($referee->fresh(), function (Referee $referee) {
            $this->assertTrue($referee->is_employed);
        });
    }
}
