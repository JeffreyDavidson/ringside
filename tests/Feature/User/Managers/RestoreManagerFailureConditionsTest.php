<?php

namespace Tests\Feature\User\Managers;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use ManagerFactory;
use Tests\TestCase;

/**
 * @group managers
 * @group users
 * @group roster
 */
class RestoreManagerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_restore_a_deleted_manager()
    {
        $this->actAs(Role::BASIC);
        $manager = ManagerFactory::new()->softDeleted()->create();

        $response = $this->restoreRequest($manager);

        $response->assertForbidden();
    }
}
