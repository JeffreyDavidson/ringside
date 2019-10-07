<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
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
        $manager = factory(Manager::class)->create(['status' => 'Example Status']);

        $this->assertEquals('Example Status', $manager->status);
    }
}
