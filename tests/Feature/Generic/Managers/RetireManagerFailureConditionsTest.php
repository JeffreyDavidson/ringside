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
class RetireManagerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_retired_manager_cannot_be_retired()
    {
        $this->actAs('administrator');
        $manager = factory(Manager::class)->states('retired')->create();

        $response = $this->retireRequest($manager);

        $response->assertForbidden();
    }
}
