<?php

namespace Tests\Feature\Generic\Managers;

use App\Enums\Role;
use App\Exceptions\CannotBeUnretiredException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\ManagerFactory;
use Tests\TestCase;

/**
 * @group managers
 * @group generics
 * @group roster
 */
class UnretireManagerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_available_manager_cannot_be_unretired()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeUnretiredException::class);

        $this->actAs(Role::ADMINISTRATOR);
        $manager = ManagerFactory::new()->available()->create();

        $response = $this->unretireRequest($manager);

        $response->assertForbidden();
    }

    /** @test */
    public function a_suspended_manager_cannot_be_unretired()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeUnretiredException::class);

        $this->actAs(Role::ADMINISTRATOR);
        $manager = ManagerFactory::new()->suspended()->create();

        $response = $this->unretireRequest($manager);

        $response->assertForbidden();
    }

    /** @test */
    public function an_injured_manager_cannot_be_unretired()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeUnretiredException::class);

        $this->actAs(Role::ADMINISTRATOR);
        $manager = ManagerFactory::new()->injured()->create();

        $response = $this->unretireRequest($manager);

        $response->assertForbidden();
    }

    /** @test */
    public function a_pending_employment_manager_cannot_be_unretired()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeUnretiredException::class);

        $this->actAs(Role::ADMINISTRATOR);
        $manager = ManagerFactory::new()->pendingEmployment()->create();

        $response = $this->unretireRequest($manager);

        $response->assertForbidden();
    }
}
