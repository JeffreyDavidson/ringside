<?php

namespace Tests\Feature\User\Managers;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\ManagerFactory;
use Tests\TestCase;

/**
 * @group managers
 * @group users
 * @group roster
 */
class RetireManagerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_retire_an_available_manager()
    {
        $this->actAs(Role::BASIC);
        $manager = ManagerFactory::new()->available()->create();

        $response = $this->retireRequest($manager);

        $response->assertForbidden();
    }

    /** @test */
    public function a_basic_user_cannot_retire_an_injured_manager()
    {
        $this->actAs(Role::BASIC);
        $manager = ManagerFactory::new()->injured()->create();

        $response = $this->retireRequest($manager);

        $response->assertForbidden();
    }

    /** @test */
    public function a_basic_user_cannot_retire_a_suspended_manager()
    {
        $this->actAs(Role::BASIC);
        $manager = ManagerFactory::new()->suspended()->create();

        $response = $this->retireRequest($manager);

        $response->assertForbidden();
    }

    /** @test */
    public function a_basic_user_cannot_retire_a_pending_introduction_manager()
    {
        $this->actAs(Role::BASIC);
        $manager = ManagerFactory::new()->pendingEmployment()->create();

        $response = $this->retireRequest($manager);

        $response->assertForbidden();
    }
}
