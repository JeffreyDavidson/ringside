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
class InjureManagerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_injure_an_available_manager()
    {
        $this->actAs('basic-user');
        $manager = factory(Manager::class)->states('available')->create();

        $response = $this->injureRequest($manager);

        $response->assertForbidden();
    }
}
