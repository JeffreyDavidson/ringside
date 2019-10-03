<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Stable;
use App\Models\Manager;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group managers
 * @group roster
 */
class ManagerTest extends TestCase
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
    public function a_manager_has_a_first_name()
    {
        $manager = factory(Manager::class)->make(['first_name' => 'John']);

        $this->assertEquals('John', $manager->first_name);
    }

    /** @test */
    public function a_manager_has_a_last_name()
    {
        $manager = factory(Manager::class)->make(['last_name' => 'Smith']);

        $this->assertEquals('Smith', $manager->last_name);
    }

    /** @test */
    public function a_manager_has_a_status()
    {
        $manager = factory(Manager::class)->make(['status' => 'Example Status']);

        $this->assertEquals('Example Status', $manager->status);
    }

    /** @test */
    public function a_manager_has_a_full_name()
    {
        $manager = factory(Manager::class)->make(['first_name' => 'John', 'last_name' => 'Smith']);

        $this->assertEquals('John Smith', $manager->full_name);
    }

    /** @test */
    public function a_manager_has_a_current_stable_after_joining()
    {
        $manager = factory(Manager::class)->states('bookable')->create();
        $stable = factory(Stable::class)->states('active')->create();

        $manager->stableHistory()->attach($stable);

        $this->assertEquals($stable->id, $manager->currentStable->id);
        $this->assertTrue($manager->stableHistory->contains($stable));
    }

    /** @test */
    public function a_stable_remains_in_a_managers_history_after_leaving()
    {
        $manager = factory(Manager::class)->create();
        $stable = factory(Stable::class)->create();
        $manager->stableHistory()->attach($stable);
        $manager->stableHistory()->detach($stable);

        $this->assertTrue($manager->previousStables->contains($stable));
    }
}
