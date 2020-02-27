<?php

namespace Tests\Feature\SuperAdmin\Managers;

use App\Enums\Role;
use App\Models\Manager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\ManagerFactory;
use Tests\TestCase;

/**
 * @group managers
 * @group superadmins
 * @group roster
 */
class DeleteManagerSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_super_administrator_can_delete_an_available_manager()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $manager = ManagerFactory::new()->available()->create();

        $response = $this->deleteRequest($manager);

        $response->assertRedirect(route('managers.index'));
        $this->assertSoftDeleted($manager);
    }

    /** @test */
    public function a_super_administrator_can_delete_a_pending_employment_manager()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $manager = ManagerFactory::new()->pendingEmployment()->create();

        $response = $this->deleteRequest($manager);

        $response->assertRedirect(route('managers.index'));
        $this->assertSoftDeleted($manager);
    }

    /** @test */
    public function a_super_administrator_can_delete_a_retired_manager()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $manager = ManagerFactory::new()->retired()->create();

        $response = $this->deleteRequest($manager);

        $response->assertRedirect(route('managers.index'));
        $this->assertSoftDeleted($manager);
    }

    /** @test */
    public function a_super_administrator_can_delete_a_suspended_manager()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $manager = ManagerFactory::new()->suspended()->create();

        $response = $this->deleteRequest($manager);

        $response->assertRedirect(route('managers.index'));
        $this->assertSoftDeleted($manager);
    }

    /** @test */
    public function a_super_administrator_can_delete_an_injured_manager()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $manager = factory(Manager::class)->states('injured')->create();

        $response = $this->deleteRequest($manager);

        $response->assertRedirect(route('managers.index'));
        $this->assertSoftDeleted($manager);
    }
}
