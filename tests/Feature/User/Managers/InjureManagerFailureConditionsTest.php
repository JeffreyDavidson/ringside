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
class InjureManagerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_injure_an_available_manager()
    {
        $this->actAs(Role::BASIC);
        $manager = ManagerFactory::new()->available()->create();

        $response = $this->injureRequest($manager);

        $response->assertForbidden();
    }
}
