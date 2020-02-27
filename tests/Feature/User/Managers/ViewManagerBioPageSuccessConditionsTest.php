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
class ViewManagerBioPageSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_can_view_their_manager_profile()
    {
        $signedInUser = $this->actAs(Role::BASIC);
        $manager = ManagerFactory::new()->create(['user_id' => $signedInUser->id]);

        $response = $this->showRequest($manager);

        $response->assertOk();
    }
}
