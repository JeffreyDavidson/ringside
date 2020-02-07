<?php

namespace Tests\Feature\Generic\TagTeams;

use TagTeamFactory;
use Tests\TestCase;
use App\Models\TagTeam;
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
        $tagTeam = TagTeamFactory::new()->retired()->create();

        $response = $this->retireRequest($tagTeam);

        $response->assertForbidden();
    }
}
