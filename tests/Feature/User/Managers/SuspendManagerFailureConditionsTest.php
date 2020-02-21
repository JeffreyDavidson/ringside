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
class SuspendManagerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_suspend_an_available_manager()
    {
        $this->actAs(Role::BASIC);
        $manager = ManagerFactory::new()->available()->create();

        $response = $this->suspendRequest($manager);

        $response->assertForbidden();
    }
}
