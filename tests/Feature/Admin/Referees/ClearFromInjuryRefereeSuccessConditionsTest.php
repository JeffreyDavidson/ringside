<?php

namespace Tests\Feature\Admin\Referees;

use App\Enums\Role;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\RefereeFactory;
use Tests\TestCase;

/**
 * @group referees
 * @group admins
 * @group roster
 */
class ClearFromInjuryRefereeSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_administrator_can_clear_an_injured_referee()
    {
        $now = now();
        Carbon::setTestNow($now);

        $this->actAs(Role::ADMINISTRATOR);
        $referee = RefereeFactory::new()->injured()->create();

        $response = $this->clearInjuryRequest($referee);

        $response->assertRedirect(route('referees.index'));
        $this->assertEquals($now->toDateTimeString(), $referee->fresh()->injuries()->latest()->first()->ended_at);
    }
}
