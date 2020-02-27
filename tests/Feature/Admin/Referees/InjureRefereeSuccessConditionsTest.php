<?php

namespace Tests\Feature\Admin\Referees;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\RefereeFactory;
use Tests\TestCase;

/**
 * @group referees
 * @group admins
 * @group roster
 */
class InjureRefereeSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_administrator_can_injure_a_bookable_referee()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $referee = RefereeFactory::new()->bookable()->create();

        $response = $this->injureRequest($referee);

        $response->assertRedirect(route('referees.index'));
        $this->assertEquals(now()->toDateTimeString(), $referee->fresh()->currentInjury->started_at);
    }
}
