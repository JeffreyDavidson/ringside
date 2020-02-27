<?php

namespace Tests\Feature\User\Managers;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\ManagerFactory;
use Tests\Factories\UserFactory;
use Tests\TestCase;

/**
 * @group managers
 * @group users
 * @group roster
 */
class ViewManagerBioPageFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_view_another_users_manager_profile()
    {
        $this->actAs(Role::BASIC);
        $otherUser = UserFactory::new()->create();
        $manager = ManagerFactory::new()->create(['user_id' => $otherUser->id]);

        $response = $this->showRequest($manager);

        $response->assertForbidden();
    }
}
