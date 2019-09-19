<?php

namespace Tests\Feature\Generic\TagTeams;

use App\Models\TagTeam;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
        $tagTeam = factory(TagTeam::class)->states('bookable')->create();

        $response = $this->restoreRequest($tagTeam);

        $response->assertNotFound();
    }

    /** @test */
    public function a_pending_employment_tag_team_cannot_be_restored()
    {
        $this->actAs('administrator');
        $tagTeam = factory(TagTeam::class)->states('pending-employment')->create();

        $response = $this->restoreRequest($tagTeam);

        $response->assertNotFound();
    }

    /** @test */
    public function a_retired_tag_team_cannot_be_restored()
    {
        $this->actAs('administrator');
        $tagTeam = factory(TagTeam::class)->states('retired')->create();

        $response = $this->restoreRequest($tagTeam);

        $response->assertNotFound();
    }

    /** @test */
    public function a_suspended_tag_team_cannot_be_restored()
    {
        $this->actAs('administrator');
        $tagTeam = factory(TagTeam::class)->states('suspended')->create();

        $response = $this->restoreRequest($tagTeam);

        $response->assertNotFound();
    }
}
