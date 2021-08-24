<?php

namespace Tests\Unit\Models;

use App\Enums\TagTeamStatus;
use App\Models\Stable;
use App\Models\TagTeam;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group tagteams
 * @group roster
 * @group models
 */
class TagTeamTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_tag_team_has_a_name()
    {
        $tagTeam = new TagTeam(['name' => 'Example Tag Team Name']);

        $this->assertEquals('Example Tag Team Name', $tagTeam->name);
    }

    /**
     * @test
     */
    public function a_tag_team_can_have_a_signature_move()
    {
        $tagTeam = new TagTeam(['signature_move' => 'Example Signature Move']);

        $this->assertEquals('Example Signature Move', $tagTeam->signature_move);
    }

    /**
     * @test
     */
    public function a_tag_team_has_a_status()
    {
        $tagTeam = new TagTeam();
        $tagTeam->setRawAttributes(['status' => 'example'], true);

        $this->assertEquals('example', $tagTeam->getRawOriginal('status'));
    }

    /**
     * @test
     */
    public function a_tag_team_status_gets_cast_as_a_tag_team_status_enum()
    {
        $tagTeam = new TagTeam();

        $this->assertInstanceOf(TagTeamStatus::class, $tagTeam->status);
    }

    /**
     * @test
     */
    public function a_wrestler_can_be_associated_to_a_user()
    {
        $this->assertInstanceOf(User::class, (new TagTeam)->user);
    }

    /**
     * @test
     */
    public function tag_team_stable()
    {
        $tagTeam = TagTeam::factory()->create();
        Stable::factory()
            ->hasAttached($tagTeam, ['joined_at' => now()->toDateTimeString()])
            ->create();

        $this->assertInstanceOf(Stable::class, $tagTeam->currentStable);
    }

    /**
     * @test
     */
    public function a_tag_team_can_have_one_current_stable()
    {
        $tagTeam = TagTeam::factory()->hasAttached(Stable::factory(), ['joined_at' => now()])->create();

        $this->assertInstanceOf(Stable::class, $tagTeam->currentStable);
    }
}
