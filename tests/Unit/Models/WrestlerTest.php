<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use App\Models\Stable;
use App\Models\TagTeam;
use App\Models\Wrestler;
use Illuminate\Database\Eloquent\Collection;
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
        Wrestler::unsetEventDispatcher();
    }

    /** @test */
    public function a_wrestler_has_a_name()
    {
        $wrestler = factory(Wrestler::class)->create(['name' => 'Kid Wonder']);

        $this->assertEquals('Kid Wonder', $wrestler->name);
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
    public function a_wrestler_has_a_signature_move()
    {
        $wrestler = factory(Wrestler::class)->create(['signature_move' => 'Powerbomb']);

        $this->assertEquals('Powerbomb', $wrestler->signature_move);
    }

    /** @test */
    public function a_wrestler_has_a_status()
    {
        $wrestler = factory(Wrestler::class)->create(['status' => 'Bookable']);

        $this->assertEquals('Bookable', $wrestler->status);
    }

    /** @test */
    public function a_wrestler_has_a_user_id()
    {
        $wrestler = factory(Wrestler::class)->create(['user_id' => 1]);

        $this->assertEquals(1, $wrestler->user_id);
    }

    /** @test */
    public function a_wrestler_can_be_associated_to_a_user()
    {
        $user = factory(User::class)->create();
        $wrestler = factory(Wrestler::class)->create(['user_id' => $user->getKey()]);

        $this->assertInstanceOf(User::class, $wrestler->user);
    }

    /** @test */
    public function a_wrestler_can_be_a_part_of_many_tag_teams()
    {
        $wrestler = factory(Wrestler::class)->create();

        $this->assertInstanceOf(Collection::class, $wrestler->tagTeamHistory);
    }
    
    /** @test */
    public function a_bookable_wrestler_can_be_a_part_of_one_bookable_tag_team()
    {
        $wrestler = factory(Wrestler::class)->states('bookable')->create();
        $tagTeam = factory(TagTeam::class)->states('bookable')->create();
        $wrestler->tagTeamHistory()->attach($tagTeam);

        $this->assertInstanceOf(TagTeam::class, $wrestler->currentTagTeam);
    }

    /** @test */
    public function a_wrestler_can_be_a_part_of_many_previous_tag_teams()
    {
        $wrestler = factory(Wrestler::class)->create();
        $tagTeam = factory(TagTeam::class)->create();
        $wrestler->tagTeamHistory()->attach($tagTeam);
        $wrestler->tagTeamHistory()->detach($tagTeam);


        $this->assertInstanceOf(Collection::class, $wrestler->previousTagTeams);
    }

    /** @test */
    public function a_wrestler_can_be_a_part_of_many_stables()
    {
        $wrestler = factory(Wrestler::class)->create();

        $this->assertInstanceOf(Collection::class, $wrestler->stableHistory);
    }
    
    /** @test */
    public function a_bookable_wrestler_can_be_a_part_of_one_active_stable()
    {
        $wrestler = factory(Wrestler::class)->states('bookable')->create();
        $stable = factory(Stable::class)->states('active')->create();
        $wrestler->stableHistory()->attach($stable);

        $this->assertInstanceOf(Stable::class, $wrestler->currentStable);
    }

    /** @test */
    public function a_wrestler_can_be_a_part_of_many_previous_stables()
    {
        $wrestler = factory(Wrestler::class)->create();
        $stable = factory(Stable::class)->create();
        $wrestler->stableHistory()->attach($stable);
        $wrestler->stableHistory()->detach($stable);


        $this->assertInstanceOf(Collection::class, $wrestler->previousStables);
    }
}
