<?php

namespace Tests\Feature\Guest\Wrestlers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Factories\WrestlerFactory;

/**
 * @group wrestlers
 * @group guests
 */
class InjureWrestlerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cannot_injure_a_wrestler()
    {
        $wrestler = WrestlerFactory::new()->create();

        $response = $this->injureRequest($wrestler);

        $response->assertRedirect(route('login'));
    }
}
