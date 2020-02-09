<?php

namespace Tests\Feature\Generic\TagTeams;

use Illuminate\Foundation\Testing\RefreshDatabase;
use TagTeamFactory;
use Tests\TestCase;

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
        $tagTeam = TagTeamFactory::new()->bookable()->create();

        $response = $this->reinstateRequest($tagTeam);

        $response->assertForbidden();
    }

    /** @test */
    public function a_pending_employment_tag_team_cannot_be_reinstated()
    {
        $this->actAs('administrator');
        $tagTeam = TagTeamFactory::new()->pendingEmployment()->create();

        $response = $this->reinstateRequest($tagTeam);

        $response->assertForbidden();
    }

    /** @test */
    public function a_retired_tag_team_cannot_be_reinstated()
    {
        $this->actAs('administrator');
        $tagTeam = TagTeamFactory::new()->retired()->create();

        $response = $this->reinstateRequest($tagTeam);

        $response->assertForbidden();
    }
}
