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
class ReinstateManagerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_reinstate_a_suspended_manager()
    {
        $this->actAs(Role::BASIC);
        $manager = ManagerFactory::new()->suspended()->create();

        $response = $this->reinstateRequest($manager);

        $response->assertForbidden();
    }
}
