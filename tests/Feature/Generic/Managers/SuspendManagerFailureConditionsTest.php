<?php

namespace Tests\Feature\Generic\Managers;

use App\Models\Manager;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group managers
 * @group generics
 * @group roster
 */
class SuspendManagerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_suspended_manager_cannot_be_suspended()
    {
        $this->actAs('administrator');
        $manager = factory(Manager::class)->states('suspended')->create();

        $response = $this->suspendRequest($manager);

        $response->assertForbidden();
    }
}
