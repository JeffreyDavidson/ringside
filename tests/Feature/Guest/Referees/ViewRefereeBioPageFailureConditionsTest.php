<?php

namespace Tests\Feature\Guest\Referees;

use Illuminate\Foundation\Testing\RefreshDatabase;
use RefereeFactory;
use Tests\TestCase;

/**
 * @group referees
 * @group guests
 * @group roster
 */
class ViewRefereeBioPageFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cannot_view_a_referee_profile()
    {
        $referee = RefereeFactory::new()->create();

        $response = $this->showRequest($referee);

        $response->assertRedirect(route('login'));
    }
}
