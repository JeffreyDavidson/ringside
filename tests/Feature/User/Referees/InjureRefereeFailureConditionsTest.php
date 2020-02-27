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
class InjureRefereeFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_injure_a_bookable_referee()
    {
        $this->actAs(Role::BASIC);
        $referee = RefereeFactory::new()->bookable()->create();

        $response = $this->injureRequest($referee);

        $response->assertForbidden();
    }
}
