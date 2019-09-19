<?php

namespace Tests\Feature\Generic\TagTeams;

use Tests\TestCase;
use App\Models\TagTeam;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group tagteams
 * @group generics
 * @group roster
 */
class ReinstateTagTeamFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_bookable_tag_team_cannot_be_reinstated()
    {
        $this->actAs('administrator');
        $tagTeam = factory(TagTeam::class)->states('bookable')->create();

        $response = $this->reinstateRequest($tagTeam);

        $response->assertForbidden();
    }

    /** @test */
    public function a_pending_employment_tag_team_cannot_be_reinstated()
    {
        $this->actAs('administrator');
        $tagTeam = factory(TagTeam::class)->states('pending-employment')->create();

        $response = $this->reinstateRequest($tagTeam);

        $response->assertForbidden();
    }

    /** @test */
    public function a_retired_tag_team_cannot_be_reinstated()
    {
        $this->actAs('administrator');
        $tagTeam = factory(TagTeam::class)->states('retired')->create();

        $response = $this->reinstateRequest($tagTeam);

        $response->assertForbidden();
    }
}
