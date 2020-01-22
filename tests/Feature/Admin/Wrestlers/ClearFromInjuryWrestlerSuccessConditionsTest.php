<?php

namespace Tests\Feature\Admin\Wrestlers;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Wrestler;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group wrestlers
 * @group admins
 * @group roster
 */
class ClearFromInjuryWrestlerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_administrator_can_recover_an_injured_wrestler()
    {
        $now = now();
        Carbon::setTestNow($now);

        $this->actAs('administrator');
        $wrestler = factory(Wrestler::class)->states('injured')->create();

        $response = $this->clearInjuryRequest($wrestler);

        $response->assertRedirect(route('wrestlers.index'));
        $this->assertEquals($now->toDateTimeString(), $wrestler->fresh()->injuries()->latest()->first()->ended_at);
    }
}
