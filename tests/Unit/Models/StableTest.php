<?php

namespace Tests\Unit\Models;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Stable;
use App\Models\Manager;
use App\Models\TagTeam;
use App\Models\Wrestler;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group stables
 * @group roster
 */
class StableTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_stable_has_a_name()
    {
        $stable = factory(Stable::class)->create(['name' => 'Example Stable Name']);

        $this->assertEquals('Example Stable Name', $stable->name);
    }

    /** @test */
    public function a_stable_can_be_employed()
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);

        $stable = factory(Stable::class)->create();

        $stable->employ();

        $this->assertEquals($now->toDateTimeString(), $stable->employment->started_at);
    }

    /** @test */
    public function a_stable_can_add_wrestlers()
    {
        $stable = factory(Stable::class)->create();
        $wrestler = factory(Wrestler::class)->create();

        $stable->addWrestlers($wrestler);

        $this->assertTrue($stable->currentMembers->contains($wrestler));
        $this->assertEquals(now()->toDateTimeString(), $stable->currentWrestlers->first()->pivot->joined_at);
    }

    /** @test */
    public function a_stable_can_add_tag_teams()
    {
        $stable = factory(Stable::class)->create();
        $tagTeam = factory(TagTeam::class)->create();

        $stable->addTagTeams($tagTeam);

        $this->assertTrue($stable->currentMembers->contains($tagTeam));
        $this->assertEquals(now()->toDateTimeString(), $stable->currentTagTeams->first()->pivot->joined_at);
    }

    /** @test */
    public function a_stable_can_add_managers()
    {
        $stable = factory(Stable::class)->create();
        $manager = factory(Manager::class)->create();

        $stable->addManagers($manager);

        $this->assertTrue($stable->members->contains($manager));
        $this->assertEquals(now()->toDateTimeString(), $stable->currentManagers->first()->pivot->joined_at);
    }

    /** @test */
    public function a_stable_can_disassemble()
    {
        $stable = factory(Stable::class)->create();
        $managers = factory(Wrestler::class, 3)->create();
        $stable->addWrestlers($managers);

        $this->assertTrue($stable->members->isEmpty());
    }
}
