<?php

namespace Tests\Feature\Admin\TagTeams;

use App\Models\TagTeam;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group tagteams
 * @group admins
 * @group roster
 */
class SuspendTagTeamSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_administrator_can_suspend_a_bookable_tag_team()
    {
        $now = now();
        Carbon::setTestNow($now);

        $this->actAs('administrator');
        $tagTeam = factory(TagTeam::class)->states('bookable')->create();

        $response = $this->suspendRequest($tagTeam);

        $response->assertRedirect(route('tag-teams.index'));
        $this->assertEquals($now->toDateTimeString(), $tagTeam->fresh()->currentSuspension->started_at);
    }
}
