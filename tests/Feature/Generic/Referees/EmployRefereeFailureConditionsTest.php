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
class EmployRefereeFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_bookable_referee_cannot_be_employed()
    {
        $this->actAs('administrator');
        $referee = factory(Referee::class)->states('bookable')->create();

        $response = $this->employRequest($referee);

        $response->assertForbidden();
    }

    /** @test */
    public function a_retired_referee_cannot_be_employed()
    {
        $this->actAs('administrator');
        $referee = factory(Referee::class)->states('retired')->create();

        $response = $this->employRequest($referee);

        $response->assertForbidden();
    }

    /** @test */
    public function a_suspended_referee_cannot_be_employed()
    {
        $this->actAs('administrator');
        $referee = factory(Referee::class)->states('suspended')->create();

        $response = $this->employRequest($referee);

        $response->assertForbidden();
    }

    /** @test */
    public function an_injured_referee_cannot_be_employed()
    {
        $this->actAs('administrator');
        $referee = factory(Referee::class)->states('injured')->create();

        $response = $this->employRequest($referee);

        $response->assertForbidden();
    }
}
