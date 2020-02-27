<?php

namespace Tests\Feature\User\Stables;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\StableFactory;
use Tests\TestCase;

/**
 * @group stables
 * @group users
 * @group roster
 */
class ViewStableBioPageSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_can_view_their_stable_profile()
    {
        $signedInUser = $this->actAs(Role::BASIC);
        $stable = StableFactory::new()->create(['user_id' => $signedInUser->id]);

        $response = $this->showRequest($stable);

        $response->assertOk();
    }
}
