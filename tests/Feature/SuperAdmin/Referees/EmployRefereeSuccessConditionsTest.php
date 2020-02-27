<?php

namespace Tests\Feature\SuperAdmin\Referees;

use App\Enums\Role;
use App\Models\Referee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\RefereeFactory;
use Tests\TestCase;

/**
 * @group referees
 * @group superadmins
 * @group roster
 */
class EmployRefereeSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_super_administrator_can_employ_a_pending_employment_referee()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $referee = RefereeFactory::new()->pendingEmployment()->create();

        $response = $this->employRequest($referee);

        $response->assertRedirect(route('referees.index'));
        tap($referee->fresh(), function (Referee $referee) {
            $this->assertTrue($referee->isCurrentlyEmployed);
        });
    }
}
