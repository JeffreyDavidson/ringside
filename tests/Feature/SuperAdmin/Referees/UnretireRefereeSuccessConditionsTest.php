<?php

namespace Tests\Feature\SuperAdmin\Referees;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\RefereeFactory;
use Tests\TestCase;

/**
 * @group referees
 * @group superadmins
 * @group roster
 */
class UnretireRefereeSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_super_administrator_can_unretire_a_retired_referee()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $referee = RefereeFactory::new()->retired()->create();

        $response = $this->unretireRequest($referee);

        $response->assertRedirect(route('referees.index'));
        $this->assertEquals(now()->toDateTimeString(), $referee->fresh()->retirements()->latest()->first()->ended_at);
    }
}
