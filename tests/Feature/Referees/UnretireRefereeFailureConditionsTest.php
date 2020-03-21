<?php

namespace Tests\Feature\User\Referees;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\RefereeFactory;
use Tests\TestCase;

/**
 * @group referees
 * @group users
 * @group roster
 */
class UnretireRefereeFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_unretire_a_retired_referee()
    {
        $this->actAs(Role::BASIC);
        $referee = RefereeFactory::new()->retired()->create();

        $response = $this->unretireRequest($referee);

        $response->assertForbidden();
    }

    /** @test */
    public function a_guest_cannot_unretire_a_retired_referee()
    {
        $referee = RefereeFactory::new()->retired()->create();

        $response = $this->unretireRequest($referee);

        $response->assertRedirect(route('login'));
    }
}
