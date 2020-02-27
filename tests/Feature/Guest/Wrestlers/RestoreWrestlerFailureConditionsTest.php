<?php

namespace Tests\Feature\Guest\Wrestlers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Factories\WrestlerFactory;

/**
 * @group wrestlers
 * @group guests
 */
class RestoreWrestlerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cannot_restore_a_deleted_wrestler()
    {
        $wrestler = WrestlerFactory::new()->softDeleted()->create();

        $response = $this->restoreRequest($wrestler);

        $response->assertRedirect(route('login'));
    }
}
