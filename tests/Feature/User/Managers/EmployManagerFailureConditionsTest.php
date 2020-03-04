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
class EmployManagerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_employ_a_pending_employment_manager()
    {
        $this->actAs(Role::BASIC);
        $manager = ManagerFactory::new()->pendingEmployment()->create();

        $response = $this->employRequest($manager);

        $response->assertForbidden();
    }
}
