<?php

namespace Tests\Feature\Generic\TagTeams;

use TagTeamFactory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group tagteams
 * @group generics
 * @group roster
 */
class DeleteTagTeamFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_already_deleted_tag_team_cannot_be_deleted()
    {
        $this->actAs('administrator');
        $tagTeam = TagTeamFactory::new()->create();
        $tagTeam->delete();

        $response = $this->deleteRequest($tagTeam);

        $response->assertNotFound();
    }
}
