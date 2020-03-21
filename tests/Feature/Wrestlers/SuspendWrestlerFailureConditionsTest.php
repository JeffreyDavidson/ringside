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
class SuspendWrestlerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_suspend_a_wrestler()
    {
        $this->actAs(Role::BASIC);
        $wrestler = WrestlerFactory::new()->create();

        $response = $this->suspendRequest($wrestler);

        $response->assertForbidden();
    }

    /** @test */
    public function a_guest_cannot_suspend_a_wrestler()
    {
        $wrestler = WrestlerFactory::new()->create();

        $response = $this->suspendRequest($wrestler);

        $response->assertRedirect(route('login'));
    }
}
