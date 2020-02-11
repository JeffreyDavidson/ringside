<?php

namespace Tests\Feature\Generic\TagTeams;

use App\Enums\Role;
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
    public function an_employed_tag_teams_name_can_be_seen_on_their_profile()
    {
        $this->actAs(Role::ADMINISTRATOR);

        $tagTeam = TagTeamFactory::new()
            ->employed()
            ->create([
                'name' => 'Tag Team 1',
            ]);

        $response = $this->showRequest($tagTeam);

        $response->assertSee('Tag Team 1');
    }

    /** @test */
    public function an_employed_tag_teams_signature_move_can_be_seen_on_their_profile()
    {
        $this->actAs(Role::ADMINISTRATOR);

        $tagTeam = TagTeamFactory::new()
            ->employed()
            ->create([
                'signature_move' => 'The Finisher',
            ]);

        $response = $this->showRequest($tagTeam);

        $response->assertSee('The Finisher');
    }

    /** @test */
    public function an_employed_tag_teams_combined_weight_can_be_seen_on_their_profile()
    {
        $this->actAs(Role::ADMINISTRATOR);

        $tagTeam = TagTeamFactory::new()
            ->withExistingWrestlers([
                WrestlerFactory::new()->bookable()->create(['weight' => 200]),
                WrestlerFactory::new()->bookable()->create(['weight' => 320]),
            ])->employed()
            ->create([]);

        $response = $this->showRequest($tagTeam);

        $response->assertSee('520 lbs.');
    }
}
