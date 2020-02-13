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
class InjureRefereeSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_super_administrator_can_injure_a_bookable_referee()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $referee = RefereeFactory::new()->bookable()->create();

        $response = $this->injureRequest($referee);

        $response->assertRedirect(route('referees.index'));
        $this->assertEquals(now()->toDateTimeString(), $referee->fresh()->currentInjury->started_at);
    }
}
