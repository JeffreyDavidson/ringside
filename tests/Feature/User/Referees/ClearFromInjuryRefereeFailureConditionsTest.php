<?php

namespace Tests\Feature\User\Referees;

use Tests\TestCase;
use App\Models\Referee;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group referees
 * @group users
 * @group roster
 */
class ClearFromInjuryRefereeFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_clear_an_injured_referee()
    {
        $this->actAs('basic-user');
        $referee = factory(Referee::class)->states('injured')->create();

        $response = $this->clearInjuryRequest($referee);

        $response->assertForbidden();
    }
}
