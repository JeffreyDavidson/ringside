<?php

namespace Tests\Unit\Models;

use TagTeamFactory;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group tagteams
 * @group roster
 */
class TagTeamTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Set up test environment for this class.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        \Event::fake();
    }

    /** @test */
    public function a_tag_team_has_a_name()
    {
        $tagTeam = TagTeamFactory::new()->create(['name' => 'Example Tag Team Name']);

        $this->assertEquals('Example Tag Team Name', $tagTeam->name);
    }

    /** @test */
    public function a_tag_team_can_have_a_signature_move()
    {
        $tagTeam = TagTeamFactory::new()->create(['signature_move' => 'Example Signature Move']);

        $this->assertEquals('Example Signature Move', $tagTeam->signature_move);
    }

    /** @test */
    public function a_tag_team_has_a_status()
    {
        $tagTeam = TagTeamFactory::new()->create(['status' => 'Example Status']);

        $this->assertEquals('Example Status', $tagTeam->getOriginal('status'));
    }

    /** @test */
    public function a_tag_team_has_a_wrestler_history()
    {
        $tagTeam = TagTeamFactory::new()->create();

        $this->assertInstanceOf(Collection::class, $tagTeam->wrestlerHistory);
    }

    /** @test */
    public function a_bookable_tag_team_has_two_current_wrestlers()
    {
        $tagTeam = TagTeamFactory::new()->bookable()->create();

        $this->assertCount(2, $tagTeam->wrestlerHistory);

        // dd($tagTeam->wrestlerHistory);
        // DB::enableQueryLog();
        // $tagTeam->currentWrestlers()->get();
        dd($tagTeam->currentWrestlers);
        // dd(DB::getQueryLog());


        $this->assertInstanceOf(Collection::class, $tagTeam->currentWrestlers);
        $this->assertCount(2, $tagTeam->currentWrestlers);
    }
}
