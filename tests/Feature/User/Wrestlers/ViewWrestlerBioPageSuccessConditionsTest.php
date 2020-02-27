<?php

namespace Tests\Feature\User\Wrestlers;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Factories\WrestlerFactory;

/**
 * @group wrestlers
 * @group users
 * @group roster
 */
class ViewWrestlerBioPageSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_can_view_their_wrestler_profile()
    {
        $signedInUser = $this->actAs(Role::BASIC);
        $wrestler = WrestlerFactory::new()->create(['user_id' => $signedInUser->id]);

        $response = $this->showRequest($wrestler);

        $response->assertOk();
    }
}
