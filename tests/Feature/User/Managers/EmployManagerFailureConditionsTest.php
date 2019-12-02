<?php

namespace Tests\Feature\User\Manager;

use Tests\TestCase;
use App\Models\Manager;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group managers
 * @group users
 * @group roster
 */
class EmployManagerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_employ_a_pending_employment_manager()
    {
        $this->actAs('basic-user');
        $manager = factory(Manager::class)->states('pending-employment')->create();

        $response = $this->employRequest($manager);

        $response->assertForbidden();
    }
}
