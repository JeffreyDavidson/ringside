<?php

namespace Tests\Feature\Guest\Wrestlers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Factories\WrestlerFactory;

/**
 * @group wrestlers
 * @group guests
 */
class SuspendWrestlerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cannot_suspend_a_wrestler()
    {
        $wrestler = WrestlerFactory::new()->create();

        $response = $this->suspendRequest($wrestler);

        $response->assertRedirect(route('login'));
    }
}
