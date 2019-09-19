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
class UnretireTagTeamFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_bookable_tag_team_cannot_be_unretired()
    {
        $this->actAs('administrator');
        $tagTeam = factory(TagTeam::class)->states('bookable')->create();

        $response = $this->unretireRequest($tagTeam);

        $response->assertForbidden();
    }

    /** @test */
    public function a_suspended_tag_team_cannot_be_unretired()
    {
        $this->actAs('administrator');
        $tagTeam = factory(TagTeam::class)->states('suspended')->create();

        $response = $this->unretireRequest($tagTeam);

        $response->assertForbidden();
    }

    /** @test */
    public function a_pending_employment_tag_team_cannot_be_unretired()
    {
        $this->actAs('administrator');
        $tagTeam = factory(TagTeam::class)->states('pending-employment')->create();

        $response = $this->unretireRequest($tagTeam);

        $response->assertForbidden();
    }
}
