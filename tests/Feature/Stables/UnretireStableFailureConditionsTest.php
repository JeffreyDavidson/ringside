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
class UnretireStableFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_unretire_a_retired_stable()
    {
        $this->actAs(Role::BASIC);
        $stable = StableFactory::new()->retired()->create();

        $response = $this->retireRequest($stable);

        $response->assertForbidden();
    }

    /** @test */
    public function a_guest_cannot_unretire_a_retired_stable()
    {
        $stable = StableFactory::new()->retired()->create();

        $response = $this->retireRequest($stable);

        $response->assertRedirect(route('login'));
    }
}
