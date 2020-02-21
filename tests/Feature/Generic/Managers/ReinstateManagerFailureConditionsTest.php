<?php

namespace Tests\Feature\Generic\Managers;

use App\Enums\Role;
use App\Exceptions\CannotBeReinstatedException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use ManagerFactory;
use Tests\TestCase;

/**
 * @group managers
 * @group generics
 * @group roster
 */
class ReinstateManagerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_available_manager_cannot_be_reinstated()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeReinstatedException::class);

        $this->actAs(Role::ADMINISTRATOR);
        $manager = ManagerFactory::new()->available()->create();

        $response = $this->reinstateRequest($manager);

        $response->assertForbidden();
    }

    /** @test */
    public function a_pending_employment_manager_cannot_be_reinstated()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeReinstatedException::class);

        $this->actAs(Role::ADMINISTRATOR);
        $manager = ManagerFactory::new()->pendingEmployment()->create();

        $response = $this->reinstateRequest($manager);

        $response->assertForbidden();
    }

    /** @test */
    public function a_retired_manager_cannot_be_reinstated()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeReinstatedException::class);

        $this->actAs(Role::ADMINISTRATOR);
        $manager = ManagerFactory::new()->retired()->create();

        $response = $this->reinstateRequest($manager);

        $response->assertForbidden();
    }

    /** @test */
    public function an_injured_manager_cannot_be_reinstated()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeReinstatedException::class);

        $this->actAs(Role::ADMINISTRATOR);
        $manager = ManagerFactory::new()->injured()->create();

        $response = $this->reinstateRequest($manager);

        $response->assertForbidden();
    }
}
