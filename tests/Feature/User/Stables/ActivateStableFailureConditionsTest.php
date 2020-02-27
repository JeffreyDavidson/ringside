<?php

namespace Tests\Feature\User\Stables;

use App\Enums\Role;
use Tests\TestCase;
use App\Models\Stable;
use Tests\Factories\StableFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group stables
 * @group users
 * @group roster
 */
class ActivateStableFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_activate_a_pending_introduction_stable()
    {
        $this->actAs(Role::BASIC);
        $stable = StableFactory::new()->pendingIntroduction()->create();

        $response = $this->activateRequest($stable);

        $response->assertForbidden();
    }
}
