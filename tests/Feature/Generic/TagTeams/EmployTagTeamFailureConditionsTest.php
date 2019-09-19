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
class EmployTagTeamFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_bookable_tag_team_cannot_be_employed()
    {
        $this->actAs('administrator');
        $tagTeam = factory(TagTeam::class)->states('employable', 'bookable')->create();

        $response = $this->employRequest($tagTeam);

        $response->assertForbidden();
    }

    /** @test */
    public function a_pending_employment_tag_team_without_wrestlers_cannot_be_employed()
    {
        $this->actAs('administrator');
        $tagTeam = factory(TagTeam::class)->states('pending-employment')->create();

        $response = $this->employRequest($tagTeam);

        $response->assertForbidden();
    }
}
