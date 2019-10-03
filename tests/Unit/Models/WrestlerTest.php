<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Stable;
use App\Models\TagTeam;
use App\Models\Wrestler;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group wrestlers
 * @group roster
 */
class WrestlerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        \Event::fake();
    }

    /** @test */
    public function a_wrestler_has_a_name()
    {
        $wrestler = factory(Wrestler::class)->create(['name' => 'Example Wrestler Name']);

        $this->assertEquals('Example Wrestler Name', $wrestler->name);
    }

    /** @test */
    public function a_wrestler_has_a_height()
    {
        $wrestler = factory(Wrestler::class)->create(['height' => 70]);

        $this->assertEquals('70', $wrestler->height);
    }

    /** @test */
    public function a_wrestler_has_a_weight()
    {
        $wrestler = factory(Wrestler::class)->create(['weight' => 210]);

        $this->assertEquals(210, $wrestler->weight);
    }

    /** @test */
    public function a_wrestler_has_a_hometown()
    {
        $wrestler = factory(Wrestler::class)->create(['hometown' => 'Los Angeles, California']);

        $this->assertEquals('Los Angeles, California', $wrestler->hometown);
    }

    /** @test */
    public function a_wrestler_can_have_a_signature_move()
    {
        $wrestler = factory(Wrestler::class)->create(['signature_move' => 'Example Signature Move']);

        $this->assertEquals('Example Signature Move', $wrestler->signature_move);
    }

    /** @test */
    public function a_wrestler_has_a_status()
    {
        $wrestler = factory(Wrestler::class)->create(['status' => 'Example Status']);

        $this->assertEquals('Example Status', $wrestler->status);
    }

    /** @test */
    public function a_wrestler_has_a_formatted_height()
    {
        $wrestler = factory(Wrestler::class)->create(['height' => 71]);

        $this->assertEquals('5\'11"', $wrestler->formatted_height);
    }

    /** @test */
    public function a_wrestler_can_get_height_in_feet()
    {
        $wrestler = factory(Wrestler::class)->create(['height' => 71]);

        $this->assertEquals(5, $wrestler->feet);
    }

    /** @test */
    public function a_wrestler_can_get_height_in_inches()
    {
        $wrestler = factory(Wrestler::class)->create(['height' => 71]);

        $this->assertEquals(11, $wrestler->inches);
    }

    /** @test */
    public function a_wrestler_has_a_current_stable_after_joining()
    {
        $wrestler = factory(Wrestler::class)->states('bookable')->create();
        $stable = factory(Stable::class)->states('active')->create();

        $wrestler->stableHistory()->attach($stable);

        $this->assertEquals($stable->id, $wrestler->currentStable->id);
        $this->assertTrue($wrestler->stableHistory->contains($stable));
    }

    /** @test */
    public function a_stable_remains_in_a_wrestlers_history_after_leaving()
    {
        $wrestler = factory(Wrestler::class)->create();
        $stable = factory(Stable::class)->create();
        $wrestler->stableHistory()->attach($stable);
        $wrestler->stableHistory()->detach($stable);

        $this->assertTrue($wrestler->previousStables->contains($stable));
    }

    /** @test */
    public function a_wrestler_has_a_current_tag_team_after_joining()
    {
        $wrestler = factory(Wrestler::class)->states('bookable')->create();
        $tagTeam = factory(TagTeam::class)->states('bookable')->create();

        $wrestler->tagTeamHistory()->attach($tagTeam);

        $this->assertEquals($tagTeam->id, $wrestler->currentTagTeam->id);
        $this->assertTrue($wrestler->tagTeamHistory->contains($tagTeam));
    }

    /** @test */
    public function a_tag_team_remains_in_a_wrestlers_history_after_leaving()
    {
        $wrestler = factory(Wrestler::class)->create();
        $tagTeam = factory(TagTeam::class)->create();

        $wrestler->tagTeamHistory()->attach($tagTeam);
        $wrestler->tagTeamHistory()->detach($tagTeam);

        $this->assertTrue($wrestler->previousTagTeams->contains($tagTeam));
    }
}
