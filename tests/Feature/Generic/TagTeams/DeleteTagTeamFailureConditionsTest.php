<?php

namespace Tests\Feature\Generic\TagTeams;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\TagTeamFactory;
use Tests\TestCase;

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
        $this->actAs(Role::ADMINISTRATOR);
        $tagTeam = TagTeamFactory::new()->softDeleted()->create();

        $response = $this->deleteRequest($tagTeam);

        $response->assertNotFound();
    }
}
