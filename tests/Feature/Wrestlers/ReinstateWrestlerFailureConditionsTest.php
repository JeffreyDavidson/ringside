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
class ReinstateWrestlerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_reinstate_a_suspended_wrestler()
    {
        $this->actAs(Role::BASIC);
        $wrestler = WrestlerFactory::new()->suspended()->create();

        $response = $this->reinstateRequest($wrestler);

        $response->assertForbidden();
    }

    /** @test */
    public function a_guest_cannot_reinstate_a_suspended_wrestler()
    {
        $wrestler = WrestlerFactory::new()->suspended()->create();

        $response = $this->reinstateRequest($wrestler);

        $response->assertRedirect(route('login'));
    }
}
