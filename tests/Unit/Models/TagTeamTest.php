<?php

namespace Tests\Unit\Models;

use App\Exceptions\CannotBeFiredException;
use App\Models\TagTeam;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

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
        $tagTeam = factory(TagTeam::class)->create(['name' => 'Example Tag Team Name']);

        $this->assertEquals('Example Tag Team Name', $tagTeam->name);
    }

    /** @test */
    public function a_tag_team_can_have_a_signature_move()
    {
        $tagTeam = factory(TagTeam::class)->create(['signature_move' => 'Example Signature Move']);

        $this->assertEquals('Example Signature Move', $tagTeam->signature_move);
    }

    /** @test */
    public function a_tag_team_has_a_status()
    {
        $tagTeam = factory(TagTeam::class)->create(['status' => 'Example Status']);

        $this->assertEquals('Example Status', $tagTeam->getOriginal('status'));
    }
}
