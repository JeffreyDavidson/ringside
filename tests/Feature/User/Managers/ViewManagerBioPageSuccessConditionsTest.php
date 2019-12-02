<?php

namespace Tests\Feature\User\Managers;

use Tests\TestCase;
use App\Models\Manager;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group managers
 * @group users
 * @group roster
 */
class ViewManagerBioPageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_can_view_their_manager_profile()
    {
        $signedInUser = $this->actAs('basic-user');
        $manager = factory(Manager::class)->create(['user_id' => $signedInUser->id]);

        $response = $this->showRequest($manager);

        $response->assertOk();
    }
}
