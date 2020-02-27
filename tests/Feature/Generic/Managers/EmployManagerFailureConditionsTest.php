<?php

namespace Tests\Feature\Generic\Managers;

use App\Enums\Role;
use App\Exceptions\CannotBeEmployedException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\ManagerFactory;
use Tests\TestCase;

/**
 * @group managers
 * @group generics
 * @group roster
 */
class EmployManagerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * A 'available' manager must already be employed to be available, so this should fail
     */
    public function an_available_manager_cannot_be_employed()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeEmployedException::class);

        $this->actAs(Role::ADMINISTRATOR);
        $manager = ManagerFactory::new()->available()->create();

        $response = $this->employRequest($manager);

        $response->assertForbidden();
    }

    /**
     * @test
     * A 'retired' manager must already be employed to be available, so this should fail
     */
    public function a_retired_manager_cannot_be_employed()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeEmployedException::class);

        $this->actAs(Role::ADMINISTRATOR);
        $manager = ManagerFactory::new()->retired()->create();

        $response = $this->employRequest($manager);

        $response->assertForbidden();
    }

    /**
     * @test
     * A 'suspended' manager must already be employed to be available, so this should fail
     */
    public function a_suspended_manager_cannot_be_employed()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeEmployedException::class);

        $this->actAs(Role::ADMINISTRATOR);
        $manager = ManagerFactory::new()->suspended()->create();

        $response = $this->employRequest($manager);

        $response->assertForbidden();
    }

    /**
     * @test
     * An 'injured' manager must already be employed to be available, so this should fail
     */
    public function an_injured_manager_cannot_be_employed()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeEmployedException::class);

        $this->actAs(Role::ADMINISTRATOR);
        $manager = ManagerFactory::new()->injured()->create();

        $response = $this->employRequest($manager);

        $response->assertForbidden();
    }
}
