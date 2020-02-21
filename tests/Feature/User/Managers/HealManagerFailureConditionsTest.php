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
class HealManagerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_recover_an_injured_manager()
    {
        $this->actAs(Role::BASIC);
        $manager = ManagerFactory::new()->injured()->create();

        $response = $this->clearInjuryRequest($manager);

        $response->assertForbidden();
    }
}
