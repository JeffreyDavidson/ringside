<?php

namespace Tests\Feature\Guest\Wrestlers;

use Tests\TestCase;
use App\Models\Wrestler;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
        $wrestler = factory(Wrestler::class)->states('pending-employment')->create();

        $response = $this->employRequest($wrestler);

        $response->assertRedirect(route('login'));
    }
}
