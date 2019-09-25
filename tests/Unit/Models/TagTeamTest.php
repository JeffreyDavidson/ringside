<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group tagteams
 * @group roster
 */
class TagTeamTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_tag_team_has_a_name()
    {
        $tagTeam = factory(TagTeam::class)->create(['name' => 'Example Name']);
    }

    /** @test */
    public function a_tag_team_can_have_a_signature_move()
    {
        $tagTeam = factory(TagTeam::class)->create(['signature_move' => 'Example Signature Move']);
    }

    /** @test */
    public function a_tag_team_has_a_status()
    {
        $tagTeam = factory(TagTeam::class)->create(['status' => 'Example Status']);
    }
}
