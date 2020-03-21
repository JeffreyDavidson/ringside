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
class ClearFromInjuryRefereeFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_clear_an_injured_referee()
    {
        $this->actAs(Role::BASIC);
        $referee = RefereeFactory::new()->injured()->create();

        $response = $this->clearInjuryRequest($referee);

        $response->assertForbidden();
    }

    /** @test */
    public function a_guest_cannot_recover_an_injured_referee()
    {
        $referee = RefereeFactory::new()->injured()->create();

        $response = $this->clearInjuryRequest($referee);

        $response->assertRedirect(route('login'));
    }
}
