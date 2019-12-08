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
class SuspendManagerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_suspend_an_available_manager()
    {
        $this->actAs('basic-user');
        $manager = factory(Manager::class)->states('available')->create();

        $response = $this->suspendRequest($manager);

        $response->assertForbidden();
    }
}
