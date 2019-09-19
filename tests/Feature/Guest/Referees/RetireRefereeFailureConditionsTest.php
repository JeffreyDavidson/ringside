<?php

namespace Tests\Feature\Guest\Referees;

use App\Models\Referee;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
        $referee = factory(Referee::class)->states('bookable')->create();

        $response = $this->retireRequest($referee);

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function a_guest_cannot_retire_a_suspended_referee()
    {
        $referee = factory(Referee::class)->states('suspended')->create();

        $response = $this->retireRequest($referee);

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function a_guest_cannot_retire_an_injured_referee()
    {
        $referee = factory(Referee::class)->states('injured')->create();

        $response = $this->retireRequest($referee);

        $response->assertRedirect(route('login'));
    }
}
