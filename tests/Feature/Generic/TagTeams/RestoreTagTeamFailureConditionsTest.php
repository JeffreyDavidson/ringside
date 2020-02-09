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
class RestoreTagTeamFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_bookable_tag_team_cannot_be_restored()
    {
        $this->actAs('administrator');
        $tagTeam = TagTeamFactory::new()->bookable()->create();

        $response = $this->restoreRequest($tagTeam);

        $response->assertNotFound();
    }

    /** @test */
    public function a_pending_employment_tag_team_cannot_be_restored()
    {
        $this->actAs('administrator');
        $tagTeam = TagTeamFactory::new()->pendingEmployment()->create();

        $response = $this->restoreRequest($tagTeam);

        $response->assertNotFound();
    }

    /** @test */
    public function a_retired_tag_team_cannot_be_restored()
    {
        $this->actAs('administrator');
        $tagTeam = TagTeamFactory::new()->retired()->create();

        $response = $this->restoreRequest($tagTeam);

        $response->assertNotFound();
    }

    /** @test */
    public function a_suspended_tag_team_cannot_be_restored()
    {
        $this->actAs('administrator');
        $tagTeam = TagTeamFactory::new()->suspended()->create();

        $response = $this->restoreRequest($tagTeam);

        $response->assertNotFound();
    }
}
