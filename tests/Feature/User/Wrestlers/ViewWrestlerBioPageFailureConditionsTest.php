<?php

namespace Tests\Feature\User\Wrestlers;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\UserFactory;
use Tests\Factories\WrestlerFactory;
use Tests\TestCase;

/**
 * @group wrestlers
 * @group users
 * @group roster
 */
class ViewWrestlerBioPageFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_view_another_users_wrestler_profile()
    {
        $this->actAs(Role::BASIC);
        $otherUser = UserFactory::new()->create();
        $wrestler = WrestlerFactory::new()->create(['user_id' => $otherUser->id]);

        $response = $this->showRequest($wrestler);

        $response->assertForbidden();
    }
}
