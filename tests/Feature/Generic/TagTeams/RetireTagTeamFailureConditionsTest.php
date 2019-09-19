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
class RetireTagTeamFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_already_retired_tag_team_cannot_be_retired()
    {
        $this->actAs('administrator');
        $tagTeam = factory(TagTeam::class)->states('retired')->create();

        $response = $this->retireRequest($tagTeam);

        $response->assertForbidden();
    }
}
