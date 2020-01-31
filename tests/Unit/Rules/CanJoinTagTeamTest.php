<?php

namespace Tests\Unit\Rules;

use TagTeamFactory;
use Tests\TestCase;
use WrestlerFactory;
use App\Models\Wrestler;
use App\Rules\CanJoinTagTeam;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CanJoinTagTeamTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_bookable_wrestler_can_join_a_tag_team()
    {
        $wrestler = factory(Wrestler::class)->states('bookable')->create();

        $result = new CanJoinTagTeam;

        $this->assertTrue($result->passes(null, $wrestler->id));
    }

    /** @test */
    public function a_pending_employment_wrestler_cannot_join_a_tag_team()
    {
        $wrestler = factory(Wrestler::class)->states('pending-employment')->create();

        $result = new CanJoinTagTeam;

        $this->assertFalse($result->passes(null, $wrestler->id));
    }

    /** @test */
    public function a_suspended_wrestler_cannot_join_a_tag_team()
    {
        $wrestler = factory(Wrestler::class)->states('suspended')->create();

        $result = new CanJoinTagTeam;

        $this->assertFalse($result->passes(null, $wrestler->id));
    }

    /** @test */
    public function an_injured_wrestler_cannot_join_a_tag_team()
    {
        $wrestler = factory(Wrestler::class)->states('injured')->create();

        $result = new CanJoinTagTeam;

        $this->assertFalse($result->passes(null, $wrestler->id));
    }

    /** @test */
    public function a_retired_wrestler_cannot_join_a_tag_team()
    {
        $wrestler = factory(Wrestler::class)->states('retired')->create();

        $result = new CanJoinTagTeam;

        $this->assertFalse($result->passes(null, $wrestler->id));
    }

    /** @test */
    public function a_bookable_wrestler_cannot_be_on_multiple_bookable_tag_teams()
    {
        $tagTeam = TagTeamFactory::new()->bookable()->create();
        dd($tagTeam);

        $this->assertFalse((new CanJoinTagTeam())->passes(null, $tagTeam->wrestlers->first()->id));
    }
}
