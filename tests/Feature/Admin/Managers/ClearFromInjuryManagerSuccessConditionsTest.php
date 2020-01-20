<?php

namespace Tests\Feature\Admin\Managers;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Manager;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group managers
 * @group admins
 * @group roster
 */
class ClearFromInjuryManagerSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_administrator_can_clear_an_injured_manager()
    {
        $now = now();
        Carbon::setTestNow($now);

        $this->actAs('administrator');
        $manager = factory(Manager::class)->states('injured')->create();

        $response = $this->clearInjuryRequest($manager);

        $response->assertRedirect(route('managers.index'));
        $this->assertEquals($now->toDateTimeString(), $manager->fresh()->injuries()->latest()->first()->ended_at);
    }
}
