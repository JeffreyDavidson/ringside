<?php

namespace Tests\Feature\Generic\TagTeams;

use TagTeamFactory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group tagteams
 * @group generics
 * @group roster
 */
class RetireTagTeamSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function both_wrestlers_are_retired_when_the_tag_team_retires()
    {
        $this->actAs('administrator');
        $tagTeam = TagTeamFactory::new()->bookable()->create();

        $this->retireRequest($tagTeam);

        $this->assertCount(1, $tagTeam->currentWrestlers[0]->retirements);
        $this->assertCount(1, $tagTeam->currentWrestlers[1]->retirements);
    }
}
