<?php

namespace Tests\Feature\Admin\TagTeams;

use App\Enums\Role;
use Tests\TestCase;
use Tests\Factories\TagTeamFactory;
use Tests\Factories\WrestlerFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group tagteams
 * @group admins
 * @group roster
 */
class ViewTagTeamBioPageSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_administrator_can_view_a_tag_team_profile()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $tagTeam = TagTeamFactory::new()->create();

        $response = $this->showRequest($tagTeam);

        $response->assertViewIs('tagteams.show');
        $this->assertTrue($response->data('tagTeam')->is($tagTeam));
    }

    /** @test */
    public function a_basic_user_can_view_their_tag_team_profile()
    {
        $signedInUser = $this->actAs(Role::BASIC);
        $tagTeam = TagTeamFactory::new()->create(['user_id' => $signedInUser->id]);

        $response = $this->showRequest($tagTeam);

        $response->assertOk();
    }

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
