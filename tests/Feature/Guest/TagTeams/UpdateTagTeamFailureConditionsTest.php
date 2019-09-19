<?php

namespace Tests\Feature\Guest\TagTeams;

use App\Models\TagTeam;
use App\Models\Wrestler;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group tagteams
 * @group guests
 * @group roster
 */
class UpdateTagTeamFailureConditionsTest extends TestCase
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
        $wrestlers = factory(Wrestler::class, 2)->create();

        return array_replace([
            'name' => 'Example Tag Team Name',
            'signature_move' => 'The Finisher',
            'hired_at' => now()->toDateTimeString(),
            'wrestlers' => $overrides['wrestlers'] ?? $wrestlers->modelKeys(),
        ], $overrides);
    }

    /** @test */
    public function a_guest_cannot_view_the_form_for_editing_a_tagteam()
    {
        $tagTeam = factory(TagTeam::class)->create();

        $response = $this->get(route('tag-teams.edit', $tagTeam));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function a_guest_cannot_update_a_tagteam()
    {
        $tagTeam = factory(TagTeam::class)->create();

        $response = $this->updateRequest($tagTeam, $this->validParams());

        $response->assertRedirect(route('login'));
    }
}
