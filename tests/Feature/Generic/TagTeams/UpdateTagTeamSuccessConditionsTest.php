<?php

namespace Tests\Feature\Generic\TagTeams;

use TagTeamFactory;
use Tests\TestCase;
use WrestlerFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group tagteams
 * @group generics
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
    public function wrestlers_of_tag_team_are_synced_when_tag_team_is_updated()
    {
        $this->actAs('administrator');
        $tagTeam = TagTeamFactory::new()->bookable()->create();
        $wrestlers = WrestlerFactory::new()->count(2)->bookable()->create();

        $this->updateRequest($tagTeam, $this->validParams(['wrestlers' => $wrestlers->modelKeys()]));

        tap($tagTeam->fresh()->currentWrestlers, function ($tagTeamWrestlers) use ($wrestlers) {
            $this->assertCount(2, $tagTeamWrestlers);
            $this->assertEquals($tagTeamWrestlers->modelKeys(), $wrestlers->modelKeys());
        });
    }

    /** @test */
    public function a_tag_team_name_is_optional()
    {
        $this->actAs('administrator');
        $tagTeam = TagTeamFactory::new()->create();

        $response = $this->updateRequest($tagTeam, $this->validParams(['name' => '']));

        $response->assertSessionDoesntHaveErrors('name');
    }

    /** @test */
    public function a_tag_team_signature_move_is_optional()
    {
        $this->actAs('administrator');
        $tagTeam = TagTeamFactory::new()->create();

        $response = $this->updateRequest($tagTeam, $this->validParams(['signature_move' => '']));

        $response->assertSessionDoesntHaveErrors('signature_move');
    }

    /** @test */
    public function a_tag_team_started_at_is_optional()
    {
        $this->actAs('administrator');
        $tagTeam = TagTeamFactory::new()->create();

        $response = $this->updateRequest($tagTeam, $this->validParams(['started_at' => '']));

        $response->assertSessionDoesntHaveErrors('started_at');
    }
}
