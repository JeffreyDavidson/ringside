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
class SuspendTagTeamFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_already_suspended_tag_team_cannot_be_suspended()
    {
        $this->actAs('administrator');
        $tagTeam = factory(TagTeam::class)->states('suspended')->create();

        $response = $this->suspendRequest($tagTeam);

        $response->assertForbidden();
    }
}
