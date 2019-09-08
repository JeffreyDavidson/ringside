<?php

namespace Tests\Feature\User\Referees;

use Tests\TestCase;
use App\Models\Referee;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group referees
 * @group users
 */
class EmployInactiveRefereeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_employ_a_pending_introduction_referee()
    {
        $this->actAs('basic-user');
        $referee = factory(Referee::class)->states('pending-introduction')->create();

        $response = $this->put(route('referees.employ', $referee));

        $response->assertForbidden();
    }
}
