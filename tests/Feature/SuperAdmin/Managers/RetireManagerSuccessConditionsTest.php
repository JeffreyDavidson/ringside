<?php

namespace Tests\Feature\SuperAdmin\Managers;

use App\Enums\Role;
use Tests\Factories\ManagerFactory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group managers
 * @group superadmins
 * @group roster
 */
class RetireManagerSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_super_administrator_can_retire_an_available_manager()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $manager = ManagerFactory::new()->available()->create();

        $response = $this->retireRequest($manager);

        $response->assertRedirect(route('managers.index'));
        $this->assertEquals(now()->toDateTimeString(), $manager->fresh()->currentRetirement->started_at);
    }

    /** @test */
    public function a_super_administrator_can_retire_an_injured_manager()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $manager = ManagerFactory::new()->injured()->create();

        $response = $this->retireRequest($manager);

        $response->assertRedirect(route('managers.index'));
        $this->assertEquals(now()->toDateTimeString(), $manager->fresh()->currentRetirement->started_at);
    }

    /** @test */
    public function a_super_administrator_can_retire_an_suspended_manager()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $manager = ManagerFactory::new()->suspended()->create();

        $response = $this->retireRequest($manager);

        $response->assertRedirect(route('managers.index'));
        $this->assertEquals(now()->toDateTimeString(), $manager->fresh()->currentRetirement->started_at);
    }
}
