<?php

namespace Tests\Integration\DataTransferObjects;

use App\DataTransferObjects\EventMatchData;
use App\Models\Wrestler;
use Tests\TestCase;

class EventMatchDataTest extends TestCase
{
    /** @test */
    public function competitors_can_be_separated_into_wrestlers_and_tag_teams()
    {
        [$wrestlerA, $wrestlerB, $wrestlerC, $wrestlerD] = Wrestler::factory()->count(4)->create();
        [$tagTeamA, $tagTeamB, $tagTeamC, $tagTeamD] = Wrestler::factory()->count(4)->create();

        $competitors = collect([
            [
                ['competitor_type' => 'wrestler', 'competitor_id' => $wrestlerA->id],
                ['competitor_type' => 'wrestler', 'competitor_id' => $wrestlerB->id],
                ['competitor_type' => 'tag_team', 'competitor_id' => $tagTeamA->id],
                ['competitor_type' => 'tag_team', 'competitor_id' => $tagTeamB->id],
            ],
            [
                ['competitor_type' => 'wrestler', 'competitor_id' => $wrestlerC->id],
                ['competitor_type' => 'wrestler', 'competitor_id' => $wrestlerD->id],
                ['competitor_type' => 'tag_team', 'competitor_id' => $tagTeamC->id],
                ['competitor_type' => 'tag_team', 'competitor_id' => $tagTeamD->id],
            ],
        ]);

        $retreivedCompetitors = EventMatchData::getCompetitors($competitors);

        $this->assertCount(2, $retreivedCompetitors);
        $this->assertArrayHasKey('wrestlers', $retreivedCompetitors[0]);
        $this->assertCount(2, $retreivedCompetitors[0]->wrestlers);
        $this->assertCollectionHas($retreivedCompetitors[0]->wrestlers, [$wrestlerA, $wrestlerB]);
        $this->assertArrayHasKey('tag_teams', $retreivedCompetitors[0]);
        $this->assertCount(2, $retreivedCompetitors[0]->tag_teams);
        $this->assertCollectionHas($retreivedCompetitors[0]->tag_teams, [$tagTeamA, $tagTeamB]);
        $this->assertArrayHasKey('wrestlers', $retreivedCompetitors[1]);
        $this->assertCount(2, $retreivedCompetitors[1]->wrestlers);
        $this->assertCollectionHas($retreivedCompetitors[1]->wrestlers, [$wrestlerC, $wrestlerD]);
        $this->assertArrayHasKey('tag_teams', $retreivedCompetitors[1]);
        $this->assertCount(2, $retreivedCompetitors[1]->tag_teams);
        $this->assertCollectionHas($retreivedCompetitors[0]->tag_teams, [$tagTeamC, $tagTeamD]);
    }
}
