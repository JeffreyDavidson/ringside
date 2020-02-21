<?php

namespace Tests\Feature\Generic\Managers;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use ManagerFactory;
use Tests\TestCase;

/**
 * @group managers
 * @group generics
 * @group roster
 */
class RestoreManagerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_available_manager_cannot_be_restored()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $manager = ManagerFactory::new()->available()->create();

        $response = $this->restoreRequest($manager);

        $response->assertNotFound();
    }

    /** @test */
    public function a_pending_employment_manager_cannot_be_restored()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $manager = ManagerFactory::new()->pendingEmployment()->create();

        $response = $this->restoreRequest($manager);

        $response->assertNotFound();
    }

    /** @test */
    public function an_injured_manager_cannot_be_restored()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $manager = ManagerFactory::new()->injured()->create();

        $response = $this->restoreRequest($manager);

        $response->assertNotFound();
    }

    /** @test */
    public function a_suspended_manager_cannot_be_restored()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $manager = ManagerFactory::new()->suspended()->create();

        $response = $this->restoreRequest($manager);

        $response->assertNotFound();
    }

    /** @test */
    public function a_retired_manager_cannot_be_restored()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $manager = ManagerFactory::new()->retired()->create();

        $response = $this->restoreRequest($manager);

        $response->assertNotFound();
    }
}
