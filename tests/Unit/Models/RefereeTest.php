<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Referee;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group referees
 * @group roster
 */
class RefereeTest extends TestCase
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
        // Referee::unsetEventDispatcher();
        \Event::fake();
    }

    /** @test */
    public function a_referee_has_a_first_name()
    {
        $referee = factory(Referee::class)->make(['first_name' => 'John']);

        $this->assertEquals('John', $referee->first_name);
    }

    /** @test */
    public function a_referee_has_a_last_name()
    {
        $referee = factory(Referee::class)->make(['last_name' => 'Smith']);

        $this->assertEquals('Smith', $referee->last_name);
    }

    /** @test */
    public function a_referee_has_a_status()
    {
        $referee = factory(Referee::class)->make(['status' => 'Example Status']);

        $this->assertEquals('Example Status', $referee->status);
    }

    /** @test */
    public function a_referee_has_a_full_name()
    {
        $referee = factory(Referee::class)->make(['first_name' => 'John', 'last_name' => 'Smith']);

        $this->assertEquals('John Smith', $referee->full_name);
    }
}
