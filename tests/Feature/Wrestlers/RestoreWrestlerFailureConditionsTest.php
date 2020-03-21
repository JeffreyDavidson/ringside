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
class RestoreWrestlerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_restore_a_deleted_wrestler()
    {
        $this->actAs(Role::BASIC);
        $wrestler = WrestlerFactory::new()->softDeleted()->create();

        $response = $this->restoreRequest($wrestler);

        $response->assertForbidden();
    }

    /** @test */
    public function a_guest_cannot_restore_a_deleted_wrestler()
    {
        $wrestler = WrestlerFactory::new()->softDeleted()->create();

        $response = $this->restoreRequest($wrestler);

        $response->assertRedirect(route('login'));
    }
}
