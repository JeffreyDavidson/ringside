<?php

namespace Tests\Feature\Guest\Wrestlers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Factories\WrestlerFactory;

/**
 * @group wrestlers
 * @group guests
 */
class RetireWrestlerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cannot_retire_a_wrestler()
    {
        $wrestler = WrestlerFactory::new()->create();

        $response = $this->retireRequest($wrestler);

        $response->assertRedirect(route('login'));
    }
}
