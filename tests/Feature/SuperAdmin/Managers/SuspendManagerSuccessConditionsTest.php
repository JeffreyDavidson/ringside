<?php

namespace Tests\Feature\SuperAdmin\Managers;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Manager;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group managers
 * @group superadmins
 * @group roster
 */
class SuspendManagerSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_super_administrator_can_suspend_an_available_manager()
    {
        $now = now();
        Carbon::setTestNow($now);

        $this->actAs('super-administrator');
        $manager = factory(Manager::class)->states('available')->create();

        $response = $this->suspendRequest($manager);

        $response->assertRedirect(route('managers.index'));
        $this->assertEquals($now->toDateTimeString(), $manager->fresh()->currentSuspension->started_at);
    }
}
