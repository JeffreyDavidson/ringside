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
    public function a_guest_cannot_employ_a_pending_introduction_wrestler()
    {
        $wrestler = factory(Wrestler::class)->states('pending-introduction')->create();

        $response = $this->put(route('wrestlers.employ', $wrestler));

        $response->assertRedirect(route('login'));
    }
}
