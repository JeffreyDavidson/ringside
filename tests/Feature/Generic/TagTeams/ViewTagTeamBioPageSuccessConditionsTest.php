<?php

namespace Tests\Feature\Generic\TagTeams;

use Illuminate\Foundation\Testing\RefreshDatabase;
use TagTeamFactory;
use Tests\TestCase;
use WrestlerFactory;

/**
 * @group tagteams
 * @group generics
 * @group roster
 */
class ViewTagTeamBioPageSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_tag_teams_data_can_be_seen_on_their_profile()
    {
        $this->actAs('administrator');

        $tagTeam = TagTeamFactory::new()
            ->withWrestlers(
                WrestlerFactory::new()->bookable()->create(['weight' => 200]),
                WrestlerFactory::new()->bookable()->create(['weight' => 320])
            )->bookable()
            ->create([
                'name' => 'Tag Team 1',
                'signature_move' => 'The Finisher',
            ]);

        $response = $this->showRequest($tagTeam);

        $response->assertSee('Tag Team 1');
        $response->assertSee('520 lbs.');
        $response->assertSee('The Finisher');
    }
}
