<?php

namespace Tests\Feature\SuperAdmin\TagTeams;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use TagTeamFactory;
use Tests\TestCase;
use WrestlerFactory;

/**
 * @group tagteams
 * @group superadmins
 * @group roster
 */
class UpdateTagTeamSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Valid parameters for request.
     *
     * @param  array $overrides
     * @return array
     */
    private function validParams($overrides = [])
    {
        $wrestlers = WrestlerFactory::new()->count(2)->bookable()->create();

        return array_replace([
            'name' => 'Example Tag Team Name',
            'signature_move' => 'The Finisher',
            'started_at' => now()->toDateTimeString(),
            'wrestlers' => $overrides['wrestlers'] ?? $wrestlers->modelKeys(),
        ], $overrides);
    }

    /** @test */
    public function a_super_administrator_can_view_the_form_for_editing_a_tagteam()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $tagTeam = TagTeamFactory::new()->create();

        $response = $this->editRequest($tagTeam);

        $response->assertViewIs('tagteams.edit');
        $this->assertTrue($response->data('tagTeam')->is($tagTeam));
    }

    /** @test */
    public function a_super_administrator_can_update_a_tag_team()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $tagTeam = TagTeamFactory::new()->create();

        $response = $this->updateRequest($tagTeam, $this->validParams());

        $response->assertRedirect(route('tag-teams.index'));
        tap($tagTeam->fresh(), function ($tagTeam) {
            $this->assertEquals('Example Tag Team Name', $tagTeam->name);
            $this->assertEquals('The Finisher', $tagTeam->signature_move);
        });
    }
}
