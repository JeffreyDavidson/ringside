<?php

namespace Tests\Feature\SuperAdmin\Managers;

use App\Enums\Role;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use ManagerFactory;
use Tests\TestCase;

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

        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $manager = ManagerFactory::new()->available()->create();

        $response = $this->suspendRequest($manager);

        $response->assertRedirect(route('managers.index'));
        $this->assertEquals($now->toDateTimeString(), $manager->fresh()->currentSuspension->started_at);
    }
}
