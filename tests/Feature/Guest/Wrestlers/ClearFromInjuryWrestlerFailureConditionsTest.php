<?php

namespace Tests\Feature\Guest\Wrestlers;

use App\Models\Wrestler;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group wrestlers
 * @group guests
 */
class ClearFromInjuryWrestlerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cannot_recover_an_injured_wrestler()
    {
        $wrestler = factory(Wrestler::class)->states('injured')->create();

        $response = $this->clearInjuryRequest($wrestler);

        $response->assertRedirect(route('login'));
    }
}
