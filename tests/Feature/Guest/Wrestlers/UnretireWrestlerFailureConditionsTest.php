<?php

namespace Tests\Feature\Guest\Wrestlers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Factories\WrestlerFactory;

/**
 * @group wrestlers
 * @group guests
 */
class UnretireWrestlerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cannot_unretire_a_retired_wrestler()
    {
        $wrestler = WrestlerFactory::new()->retired()->create();

        $response = $this->unretireRequest($wrestler);

        $response->assertRedirect(route('login'));
    }
}
