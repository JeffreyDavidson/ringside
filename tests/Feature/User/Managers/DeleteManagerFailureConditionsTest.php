<?php

namespace Tests\Feature\User\Managers;

use App\Models\Manager;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group managers
 * @group users
 * @group roster
 */
class DeleteManagerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_delete_a_bookable_manager()
    {
        $this->actAs('basic-user');
        $manager = factory(Manager::class)->states('bookable')->create();

        $response = $this->deleteRequest($manager);

        $response->assertForbidden();
    }

    /** @test */
    public function a_basic_user_cannot_delete_a_retired_manager()
    {
        $this->actAs('basic-user');
        $manager = factory(Manager::class)->states('retired')->create();

        $response = $this->deleteRequest($manager);

        $response->assertForbidden();
    }

    /** @test */
    public function a_basic_user_cannot_delete_an_injured_manager()
    {
        $this->actAs('basic-user');
        $manager = factory(Manager::class)->states('injured')->create();

        $response = $this->deleteRequest($manager);

        $response->assertForbidden();
    }

    /** @test */
    public function a_basic_user_cannot_delete_a_suspended_manager()
    {
        $this->actAs('basic-user');
        $manager = factory(Manager::class)->states('suspended')->create();

        $response = $this->deleteRequest($manager);

        $response->assertForbidden();
    }

    /** @test */
    public function a_basic_user_cannot_delete_a_pending_introduction_manager()
    {
        $this->actAs('basic-user');
        $manager = factory(Manager::class)->states('pending-employment')->create();

        $response = $this->deleteRequest($manager);

        $response->assertForbidden();
    }
}
