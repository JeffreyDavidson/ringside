<?php

namespace Tests\Feature\SuperAdmin\Wrestlers;

use App\Enums\Role;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Factories\WrestlerFactory;

/**
 * @group wrestlers
 * @group superadmins
 * @group roster
 */
class ClearFromInjuryWrestlerSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_super_administrator_can_recover_an_injured_wrestler()
    {
        $now = now();
        Carbon::setTestNow($now);

        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $wrestler = WrestlerFactory::new()->injured()->create();

        $response = $this->clearInjuryRequest($wrestler);

        $response->assertRedirect(route('wrestlers.index'));
        $this->assertEquals($now->toDateTimeString(), $wrestler->fresh()->injuries()->latest()->first()->ended_at);
    }
}
