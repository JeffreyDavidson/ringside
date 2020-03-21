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
class RestoreStableFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_restore_a_deleted_stable()
    {
        $this->actAs(Role::BASIC);
        $stable = StableFactory::new()->softDeleted()->create();

        $response = $this->restoreRequest($stable);

        $response->assertForbidden();
    }

    /** @test */
    public function a_guest_cannot_restore_a_deleted_stable()
    {
        $stable = StableFactory::new()->softDeleted()->create();

        $response = $this->restoreRequest($stable);

        $response->assertRedirect(route('login'));
    }
}
