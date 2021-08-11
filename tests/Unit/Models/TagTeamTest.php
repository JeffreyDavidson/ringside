<?php

namespace Tests\Unit\Models;

use App\Enums\TagTeamStatus;
use App\Models\TagTeam;
use App\Models\User;
use Tests\TestCase;

/**
 * @group tagteams
 * @group roster
 * @group models
 */
class TagTeamTest extends TestCase
{
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
}
