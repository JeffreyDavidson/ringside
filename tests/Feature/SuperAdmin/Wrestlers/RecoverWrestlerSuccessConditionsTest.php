<?php

namespace Tests\Feature\SuperAdmin\Wrestlers;

use App\Models\Wrestler;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group wrestlers
 * @group superadmins
 * @group roster
 */
class RecoverWrestlerSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_super_administrator_can_recover_an_injured_wrestler()
    {
        $this->actAs('super-administrator');
        $wrestler = factory(Wrestler::class)->states('injured')->create();

        $response = $this->recoverRequest($wrestler);

        $response->assertRedirect(route('wrestlers.index'));
        $this->assertEquals(now()->toDateTimeString(), $wrestler->fresh()->injuries()->latest()->first()->ended_at);
    }
}
