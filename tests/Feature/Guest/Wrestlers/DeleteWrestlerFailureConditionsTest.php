<?php

namespace Tests\Feature\Guest\Wrestlers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Factories\WrestlerFactory;

/**
 * @group wrestlers
 * @group guests
 */
class DeleteWrestlerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cannot_delete_a_wrestler()
    {
        $wrestler = WrestlerFactory::new()->create();

        $response = $this->deleteRequest($wrestler);

        $response->assertRedirect(route('login'));
    }
}
