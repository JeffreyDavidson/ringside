<?php

namespace Tests\Feature\Guest\Wrestlers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Factories\WrestlerFactory;

/**
 * @group wrestlers
 * @group guests
 */
class ReinstateWrestlerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cannot_reinstate_a_suspended_wrestler()
    {
        $wrestler = WrestlerFactory::new()->suspended()->create();

        $response = $this->reinstateRequest($wrestler);

        $response->assertRedirect(route('login'));
    }
}
