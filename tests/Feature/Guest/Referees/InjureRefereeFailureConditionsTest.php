<?php

namespace Tests\Feature\Guest\Referees;

use App\Models\Referee;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group referees
 * @group guests
 * @group roster
 */
class InjureRefereeFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cannot_injure_a_bookable_referee()
    {
        $referee = factory(Referee::class)->states('bookable')->create();

        $response = $this->injureRequest($referee);

        $response->assertRedirect(route('login'));
    }
}
