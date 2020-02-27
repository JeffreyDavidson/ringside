<?php

namespace Tests\Feature\Admin\Managers;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\ManagerFactory;
use Tests\TestCase;

/**
 * @group managers
 * @group admins
 * @group roster
 */
class ViewManagerBioPageSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_administrator_can_view_an_available_manager_profile()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $manager = ManagerFactory::new()->available()->create();

        $response = $this->showRequest($manager);

        $response->assertViewIs('managers.show');
        $this->assertTrue($response->data('manager')->is($manager));
    }

    /** @test */
    public function an_administrator_can_view_an_injured_manager_profile()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $manager = ManagerFactory::new()->injured()->create();

        $response = $this->showRequest($manager);

        $response->assertViewIs('managers.show');
        $this->assertTrue($response->data('manager')->is($manager));
    }

    /** @test */
    public function an_administrator_can_view_a_suspended_manager_profile()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $manager = ManagerFactory::new()->suspended()->create();

        $response = $this->showRequest($manager);

        $response->assertViewIs('managers.show');
        $this->assertTrue($response->data('manager')->is($manager));
    }

    /** @test */
    public function an_administrator_can_view_a_retired_manager_profile()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $manager = ManagerFactory::new()->retired()->create();

        $response = $this->showRequest($manager);

        $response->assertViewIs('managers.show');
        $this->assertTrue($response->data('manager')->is($manager));
    }

    /** @test */
    public function an_administrator_can_view_a_pending_employment_manager_profile()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $manager = ManagerFactory::new()->pendingEmployment()->create();

        $response = $this->showRequest($manager);

        $response->assertViewIs('managers.show');
        $this->assertTrue($response->data('manager')->is($manager));
    }
}
