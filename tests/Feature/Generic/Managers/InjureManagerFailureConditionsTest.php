<?php

namespace Tests\Feature\Generic\Managers;

use App\Enums\Role;
use App\Exceptions\CannotBeInjuredException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use ManagerFactory;
use Tests\TestCase;

/**
 * @group managers
 * @group generics
 * @group roster
 */
class InjureManagerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_injured_manager_cannot_be_injured()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeInjuredException::class);

        $this->actAs(Role::ADMINISTRATOR);
        $manager = ManagerFactory::new()->injured()->create();

        $response = $this->injureRequest($manager);

        $response->assertForbidden();
    }

    /** @test */
    public function a_pending_employment_manager_cannot_be_injured()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeInjuredException::class);

        $this->actAs(Role::ADMINISTRATOR);
        $manager = ManagerFactory::new()->pendingEmployment()->create();

        $response = $this->injureRequest($manager);

        $response->assertForbidden();
    }

    /** @test */
    public function a_suspended_manager_cannot_be_injured()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeInjuredException::class);

        $this->actAs(Role::ADMINISTRATOR);
        $manager = ManagerFactory::new()->suspended()->create();

        $response = $this->injureRequest($manager);

        $response->assertForbidden();
    }

    /** @test */
    public function a_retired_manager_cannot_be_injured()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeInjuredException::class);

        $this->actAs(Role::ADMINISTRATOR);
        $manager = ManagerFactory::new()->retired()->create();

        $response = $this->injureRequest($manager);

        $response->assertForbidden();
    }
}
