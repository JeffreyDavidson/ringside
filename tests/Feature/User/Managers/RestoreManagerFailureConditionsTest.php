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
class RestoreManagerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_restore_a_deleted_manager()
    {
        $this->actAs('basic-user');
        $manager = factory(Manager::class)->create();
        $manager->delete();

        $response = $this->restoreRequest($manager);

        $response->assertForbidden();
    }
}
