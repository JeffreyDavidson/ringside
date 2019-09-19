<?php

namespace Tests\Feature\SuperAdmin\TagTeams;

use App\Models\TagTeam;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group tagteams
 * @group superadmins
 * @group roster
 */
class RetireTagTeamSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_super_administrator_can_retire_a_bookable_tag_team()
    {
        $this->actAs('super-administrator');
        $tagTeam = factory(TagTeam::class)->states('bookable')->create();

        $response = $this->retireRequest($tagTeam);

        $response->assertRedirect(route('tag-teams.index'));
        $this->assertEquals(now()->toDateTimeString(), $tagTeam->fresh()->retirement->started_at);
    }

    /** @test */
    public function a_super_administrator_can_retire_a_suspended_tag_team()
    {
        $this->actAs('super-administrator');
        $tagTeam = factory(TagTeam::class)->states('suspended')->create();

        $response = $this->retireRequest($tagTeam);

        $response->assertRedirect(route('tag-teams.index'));
        $this->assertEquals(now()->toDateTimeString(), $tagTeam->fresh()->retirement->started_at);
    }
}
