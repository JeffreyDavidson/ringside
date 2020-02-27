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
class ClearFromInjuryRefereeFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cannot_recover_an_injured_referee()
    {
        $referee = RefereeFactory::new()->injured()->create();

        $response = $this->clearInjuryRequest($referee);

        $response->assertRedirect(route('login'));
    }
}
