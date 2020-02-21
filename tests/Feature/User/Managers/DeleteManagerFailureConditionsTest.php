<?php

namespace Tests\Feature\User\Managers;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use ManagerFactory;
use Tests\TestCase;

/**
 * @group managers
 * @group users
 * @group roster
 */
class DeleteManagerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_delete_an_available_manager()
    {
        $this->actAs(Role::BASIC);
        $manager = ManagerFactory::new()->available()->create();

        $response = $this->deleteRequest($manager);

        $response->assertForbidden();
    }

    /** @test */
    public function a_basic_user_cannot_delete_a_retired_manager()
    {
        $this->actAs(Role::BASIC);
        $manager = ManagerFactory::new()->retired()->create();

        $response = $this->deleteRequest($manager);

        $response->assertForbidden();
    }

    /** @test */
    public function a_basic_user_cannot_delete_an_injured_manager()
    {
        $this->actAs(Role::BASIC);
        $manager = ManagerFactory::new()->injured()->create();

        $response = $this->deleteRequest($manager);

        $response->assertForbidden();
    }

    /** @test */
    public function a_basic_user_cannot_delete_a_suspended_manager()
    {
        $this->actAs(Role::BASIC);
        $manager = ManagerFactory::new()->suspended()->create();

        $response = $this->deleteRequest($manager);

        $response->assertForbidden();
    }

    /** @test */
    public function a_basic_user_cannot_delete_a_pending_introduction_manager()
    {
        $this->actAs(Role::BASIC);
        $manager = ManagerFactory::new()->pendingEmployment()->create();

        $response = $this->deleteRequest($manager);

        $response->assertForbidden();
    }
}
