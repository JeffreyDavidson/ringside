<?php

namespace Tests\Feature\SuperAdmin\Managers;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use ManagerFactory;
use Tests\TestCase;

/**
 * @group managers
 * @group superadmins
 * @group roster
 */
class ReinstateManagerSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_super_administrator_can_reinstate_a_suspended_manager()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $manager = ManagerFactory::new()->suspended()->create();

        $response = $this->reinstateRequest($manager);

        $response->assertRedirect(route('managers.index'));
        $this->assertEquals(now()->toDateTimeString(), $manager->fresh()->suspensions()->latest()->first()->ended_at);
    }
}
