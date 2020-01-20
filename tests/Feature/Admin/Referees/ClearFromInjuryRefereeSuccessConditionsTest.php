<?php

namespace Tests\Feature\Admin\Referees;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Referee;
use Illuminate\Foundation\Testing\RefreshDatabase;

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

        $this->actAs('administrator');
        $referee = factory(Referee::class)->states('injured')->create();

        $response = $this->clearInjuryRequest($referee);

        $response->assertRedirect(route('referees.index'));
        $this->assertEquals($now->toDateTimeString(), $referee->fresh()->injuries()->latest()->first()->ended_at);
    }
}
