<?php

namespace Tests\Feature\Wrestlers;

use App\Enums\Role;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\WrestlerFactory;
use Tests\TestCase;

/**
 * @group wrestlers
 * @group roster
 */
class ClearFromInjuryWrestlerSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_administrator_can_recover_an_injured_wrestler()
    {
        $now = now();
        Carbon::setTestNow($now);

        $this->actAs(Role::ADMINISTRATOR);
        $wrestler = WrestlerFactory::new()->injured()->create();

        $response = $this->clearInjuryRequest($wrestler);

        $response->assertRedirect(route('wrestlers.index'));
        $this->assertEquals($now->toDateTimeString(), $wrestler->fresh()->injuries()->latest()->first()->ended_at);
    }
}
