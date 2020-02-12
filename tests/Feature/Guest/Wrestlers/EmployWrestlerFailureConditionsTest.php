<?php

namespace Tests\Feature\Guest\Wrestlers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use WrestlerFactory;

/**
 * @group wrestlers
 * @group guests
 */
class EmployWrestlerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cannot_employ_a_pending_employment_wrestler()
    {
        $wrestler = WrestlerFactory::new()->pendingEmployment()->create();

        $response = $this->employRequest($wrestler);

        $response->assertRedirect(route('login'));
    }
}
