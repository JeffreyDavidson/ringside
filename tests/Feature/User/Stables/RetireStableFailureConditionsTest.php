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
class RetireStableFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_retire_an_active_stable()
    {
        $this->actAs(Role::BASIC);
        $stable = StableFactory::new()->active()->create();

        $response = $this->retireRequest($stable);

        $response->assertForbidden();
    }
}
