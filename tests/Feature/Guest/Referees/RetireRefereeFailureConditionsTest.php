<?php

namespace Tests\Feature\Guest\Referees;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\RefereeFactory;
use Tests\TestCase;

/**
 * @group referees
 * @group guests
 * @group roster
 */
class RetireRefereeFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cannot_retire_a_referee()
    {
        $referee = RefereeFactory::new()->bookable()->create();

        $response = $this->retireRequest($referee);

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function a_guest_cannot_retire_a_suspended_referee()
    {
        $referee = RefereeFactory::new()->suspended()->create();

        $response = $this->retireRequest($referee);

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function a_guest_cannot_retire_an_injured_referee()
    {
        $referee = RefereeFactory::new()->injured()->create();

        $response = $this->retireRequest($referee);

        $response->assertRedirect(route('login'));
    }
}
