<?php

namespace Tests\Unit\Policies;

use Tests\TestCase;
use App\Models\User;
use App\Models\Manager;
use App\Policies\ManagerPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group managers
 * @group roster
 */
class ManagerPolicyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->policy = new ManagerPolicy;
        Manager::unsetEventDispatcher();
    }

    /** @test */
    public function a_basic_user_cannot_create_a_manager()
    {
        $user = factory(User::class)->states('basic-user')->create();

        $this->assertFalse($this->policy->create($user));
    }

    /** @test */
    public function an_administrator_can_create_a_manager()
    {
        $user = factory(User::class)->states('administrator')->create();

        $this->assertTrue($this->policy->create($user));
    }

    /** @test */
    public function a_super_administrator_can_create_a_manager()
    {
        $user = factory(User::class)->states('super-administrator')->create();

        $this->assertTrue($this->policy->create($user));
    }

    /** @test */
    public function a_basic_user_cannot_update_a_manager()
    {
        $user = factory(User::class)->states('basic-user')->create();

        $this->assertFalse($this->policy->update($user));
    }

    /** @test */
    public function an_administrator_can_update_a_manager()
    {
        $user = factory(User::class)->states('administrator')->create();

        $this->assertTrue($this->policy->update($user));
    }

    /** @test */
    public function a_super_administrator_can_update_a_manager()
    {
        $user = factory(User::class)->states('super-administrator')->create();

        $this->assertTrue($this->policy->update($user));
    }

    /** @test */
    public function a_basic_user_cannot_delete_a_manager()
    {
        $user = factory(User::class)->states('basic-user')->create();

        $this->assertFalse($this->policy->delete($user));
    }

    /** @test */
    public function an_administrator_can_delete_a_manager()
    {
        $user = factory(User::class)->states('administrator')->create();

        $this->assertTrue($this->policy->delete($user));
    }

    /** @test */
    public function a_super_administrator_can_delete_a_manager()
    {
        $user = factory(User::class)->states('super-administrator')->create();

        $this->assertTrue($this->policy->delete($user));
    }

    /** @test */
    public function a_basic_user_cannot_restore_a_manager()
    {
        $user = factory(User::class)->states('basic-user')->create();

        $this->assertFalse($this->policy->restore($user));
    }

    /** @test */
    public function an_administrator_can_restore_a_manager()
    {
        $user = factory(User::class)->states('administrator')->create();

        $this->assertTrue($this->policy->restore($user));
    }

    /** @test */
    public function a_super_administrator_can_restore_a_manager()
    {
        $user = factory(User::class)->states('super-administrator')->create();

        $this->assertTrue($this->policy->restore($user));
    }

    /** @test */
    public function a_basic_user_cannot_view_list_of_managers()
    {
        $user = factory(User::class)->states('basic-user')->create();

        $this->assertFalse($this->policy->viewList($user));
    }

    /** @test */
    public function an_administrator_can_view_list_of_managers()
    {
        $user = factory(User::class)->states('administrator')->create();

        $this->assertTrue($this->policy->viewList($user));
    }

    /** @test */
    public function a_super_administrator_can_view_list_of_managers()
    {
        $user = factory(User::class)->states('super-administrator')->create();

        $this->assertTrue($this->policy->viewList($user));
    }

    /** @test */
    public function a_basic_user_cannot_employ_a_manager()
    {
        $user = factory(User::class)->states('basic-user')->create();

        $this->assertFalse($this->policy->employ($user));
    }

    /** @test */
    public function an_administrator_can_employ_a_manager()
    {
        $user = factory(User::class)->states('administrator')->create();

        $this->assertTrue($this->policy->employ($user));
    }

    /** @test */
    public function a_super_administrator_can_employ_a_manager()
    {
        $user = factory(User::class)->states('super-administrator')->create();

        $this->assertTrue($this->policy->employ($user));
    }

    /** @test */
    public function a_basic_user_cannot_injure_a_manager()
    {
        $user = factory(User::class)->states('basic-user')->create();

        $this->assertFalse($this->policy->injure($user));
    }

    /** @test */
    public function an_administrator_can_injure_a_manager()
    {
        $user = factory(User::class)->states('administrator')->create();

        $this->assertTrue($this->policy->injure($user));
    }

    /** @test */
    public function a_super_administrator_can_injure_a_manager()
    {
        $user = factory(User::class)->states('super-administrator')->create();

        $this->assertTrue($this->policy->injure($user));
    }

    /** @test */
    public function a_basic_user_cannot_recover_a_manager()
    {
        $user = factory(User::class)->states('basic-user')->create();

        $this->assertFalse($this->policy->recover($user));
    }

    /** @test */
    public function an_administrator_can_recover_a_manager()
    {
        $user = factory(User::class)->states('administrator')->create();

        $this->assertTrue($this->policy->recover($user));
    }

    /** @test */
    public function a_super_administrator_can_recover_a_manager()
    {
        $user = factory(User::class)->states('super-administrator')->create();

        $this->assertTrue($this->policy->recover($user));
    }

    /** @test */
    public function a_basic_user_cannot_suspend_a_manager()
    {
        $user = factory(User::class)->states('basic-user')->create();

        $this->assertFalse($this->policy->suspend($user));
    }

    /** @test */
    public function an_administrator_can_suspend_a_manager()
    {
        $user = factory(User::class)->states('administrator')->create();
        $manager = factory(Manager::class)->create();

        $this->assertTrue($this->policy->suspend($user));
    }

    /** @test */
    public function a_super_administrator_can_suspend_a_manager()
    {
        $user = factory(User::class)->states('super-administrator')->create();

        $this->assertTrue($this->policy->suspend($user));
    }

    /** @test */
    public function a_basic_user_cannot_reinstate_a_manager()
    {
        $user = factory(User::class)->states('basic-user')->create();

        $this->assertFalse($this->policy->reinstate($user));
    }

    /** @test */
    public function an_administrator_can_reinstate_a_manager()
    {
        $user = factory(User::class)->states('administrator')->create();

        $this->assertTrue($this->policy->reinstate($user));
    }

    /** @test */
    public function a_super_administrator_can_reinstate_a_manager()
    {
        $user = factory(User::class)->states('super-administrator')->create();

        $this->assertTrue($this->policy->reinstate($user));
    }

    /** @test */
    public function a_basic_user_cannot_retire_a_manager()
    {
        $user = factory(User::class)->states('basic-user')->create();

        $this->assertFalse($this->policy->retire($user));
    }

    /** @test */
    public function an_administrator_can_retire_a_manager()
    {
        $user = factory(User::class)->states('administrator')->create();

        $this->assertTrue($this->policy->retire($user));
    }

    /** @test */
    public function a_super_administrator_can_retire_a_manager()
    {
        $user = factory(User::class)->states('super-administrator')->create();

        $this->assertTrue($this->policy->retire($user));
    }

    /** @test */
    public function a_basic_user_cannot_unretire_a_manager()
    {
        $user = factory(User::class)->states('basic-user')->create();

        $this->assertFalse($this->policy->unretire($user));
    }

    /** @test */
    public function an_administrator_can_unretire_a_manager()
    {
        $user = factory(User::class)->states('administrator')->create();

        $this->assertTrue($this->policy->unretire($user));
    }

    /** @test */
    public function a_super_administrator_can_unretire_a_manager()
    {
        $user = factory(User::class)->states('super-administrator')->create();

        $this->assertTrue($this->policy->unretire($user));
    }

    /** @test */
    public function a_basic_user_cannot_view_a_manager_profile_not_owned_by_user()
    {
        $user = factory(User::class)->states('basic-user')->create();
        $manager = factory(Manager::class)->create();

        $this->assertFalse($this->policy->view($user, $manager));
    }

    /** @test */
    public function an_administrator_can_view_a_manager_profile()
    {
        $user = factory(User::class)->states('administrator')->create();
        $manager = factory(Manager::class)->create();

        $this->assertTrue($this->policy->view($user, $manager));
    }

    /** @test */
    public function a_super_administrator_can_view_a_manager_profile()
    {
        $user = factory(User::class)->states('super-administrator')->create();
        $manager = factory(Manager::class)->create();

        $this->assertTrue($this->policy->view($user, $manager));
    }

    /** @test */
    public function a_basic_user_cannot_view_a_manager_profile_owned_by_user()
    {
        $user = factory(User::class)->states('basic-user')->create();
        $manager = factory(Manager::class)->create(['user_id' => $user]);

        $this->assertTrue($this->policy->view($user, $manager));
    }
}
