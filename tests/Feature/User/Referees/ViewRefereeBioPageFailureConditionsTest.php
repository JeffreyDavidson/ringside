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
class ViewRefereeBioPageFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_view_a_referee_profile()
    {
        $this->actAs(Role::BASIC);
        $referee = RefereeFactory::new()->create();

        $response = $this->showRequest($referee);

        $response->assertForbidden();
    }
}
