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
class UnretireTagTeamSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function unretiring_a_tag_team_makes_both_wrestlers_bookable()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $tagTeam = TagTeamFactory::new()->retired()->create();

        $response = $this->unretireRequest($tagTeam);

        $response->assertRedirect(route('tag-teams.index'));
        $this->assertCount(2, $tagTeam->fresh()->currentWrestlers->filter->isBookable());
    }
}
