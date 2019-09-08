<?php

namespace Tests\Feature\User\Manager;

use Tests\TestCase;
use App\Models\Manager;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group managers
 * @group users
 */
class EmployManagerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_employ_a_pending_introduction_manager()
    {
        $this->actAs('basic-user');
        $manager = factory(Manager::class)->states('pending-introduction')->create();

        $response = $this->put(route('managers.employ', $manager));

        $response->assertForbidden();
    }
}
