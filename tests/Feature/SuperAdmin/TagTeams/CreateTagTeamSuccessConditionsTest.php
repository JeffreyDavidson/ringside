<?php

namespace Tests\Feature\SuperAdmin\TagTeams;

use Tests\TestCase;
use App\Models\TagTeam;
use App\Models\Wrestler;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group tagteams
 * @group superadmins
 * @group roster
 */
class CreateTagTeamSuccessConditionsTest extends TestCase
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
        $wrestlers = factory(Wrestler::class, 2)->states('bookable')->create();

        return array_replace_recursive([
            'name' => 'Example Tag Team Name',
            'signature_move' => 'The Finisher',
            'started_at' => now()->toDateTimeString(),
            'wrestlers' => $overrides['wrestlers'] ?? $wrestlers->modelKeys(),
        ], $overrides);
    }

    /** @test */
    public function a_super_administrator_can_view_the_form_for_creating_a_tag_team()
    {
        $this->actAs('super-administrator');

        $response = $this->get(route('tag-teams.create'));

        $response->assertViewIs('tagteams.create');
        $response->assertViewHas('tagTeam', new TagTeam);
    }

    /** @test */
    public function a_super_administrator_can_create_a_tag_team()
    {
        $this->actAs('super-administrator');

        $response = $this->storeRequest('tag-team', $this->validParams());

        $response->assertRedirect(route('tag-teams.index'));
        tap(TagTeam::first(), function ($tagTeam) {
            $this->assertEquals('Example Tag Team Name', $tagTeam->name);
            $this->assertEquals('The Finisher', $tagTeam->signature_move);
        });
    }
}
