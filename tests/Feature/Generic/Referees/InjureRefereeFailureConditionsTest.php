<?php

namespace Tests\Feature\Generic\Referees;

use Tests\TestCase;
use App\Models\Referee;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group referees
 * @group generics
 * @group roster
 */
class InjureRefereeFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_already_injured_referee_cannot_be_injured()
    {
        $this->actAs('administrator');
        $referee = factory(Referee::class)->states('injured')->create();

        $response = $this->injureRequest($referee);

        $response->assertForbidden();
    }
}
