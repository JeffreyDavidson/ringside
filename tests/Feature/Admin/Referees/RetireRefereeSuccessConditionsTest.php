<?php

namespace Tests\Feature\Admin\Referees;

use Tests\TestCase;
use App\Models\Referee;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group referees
 * @group admins
 * @group roster
 */
class RetireRefereeSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_administrator_can_retire_a_bookable_referee()
    {
        $this->actAs('administrator');
        $referee = factory(Referee::class)->states('bookable')->create();

        $response = $this->retireRequest($referee);

        $response->assertRedirect(route('referees.index'));
        $this->assertEquals(now()->toDateTimeString(), $referee->fresh()->currentRetirement->started_at);
    }

    /** @test */
    public function an_administrator_can_retire_an_injured_referee()
    {
        $this->actAs('administrator');
        $referee = factory(Referee::class)->states('injured')->create();

        $response = $this->retireRequest($referee);

        $response->assertRedirect(route('referees.index'));
        $this->assertEquals(now()->toDateTimeString(), $referee->fresh()->currentRetirement->started_at);
    }

    /** @test */
    public function an_administrator_can_retire_a_suspended_referee()
    {
        $this->actAs('administrator');
        $referee = factory(Referee::class)->states('suspended')->create();

        $response = $this->retireRequest($referee);

        $response->assertRedirect(route('referees.index'));
        $this->assertEquals(now()->toDateTimeString(), $referee->fresh()->currentRetirement->started_at);
    }
}
