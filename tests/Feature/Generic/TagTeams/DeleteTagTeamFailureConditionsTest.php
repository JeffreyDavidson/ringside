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
class DeleteTagTeamFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_already_deleted_tag_team_cannot_be_deleted()
    {
        $this->actAs('administrator');
        $tagTeam = factory(TagTeam::class)->create();
        $tagTeam->delete();

        $response = $this->deleteRequest($tagTeam);

        $response->assertNotFound();
    }
}
