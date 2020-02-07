<?php

namespace Tests\Feature\Admin\TagTeams;

use TagTeamFactory;
use Tests\TestCase;
use WrestlerFactory;
use App\Models\TagTeam;
use App\Models\Wrestler;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group tagteams
 * @group admins
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
    public function an_administrator_can_view_the_form_for_editing_a_tag_team()
    {
        $this->actAs('administrator');
        $tagTeam = TagTeamFactory::new()->create();

        $response = $this->get(route('tag-teams.edit', $tagTeam));

        $response->assertViewIs('tagteams.edit');
        $this->assertTrue($response->data('tagTeam')->is($tagTeam));
    }

    /** @test */
    public function an_administrator_can_update_a_tag_team()
    {
        $this->actAs('administrator');
        $tagTeam = TagTeamFactory::new()->create();

        $response = $this->updateRequest($tagTeam, $this->validParams());

        $response->assertRedirect(route('tag-teams.index'));
        tap($tagTeam->fresh(), function ($tagTeam) {
            $this->assertEquals('Example Tag Team Name', $tagTeam->name);
            $this->assertEquals('The Finisher', $tagTeam->signature_move);
        });
    }
}
