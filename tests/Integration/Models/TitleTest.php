<?php

namespace Tests\Integration\Models;

use App\Models\Title;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group titles
 */
class TitleTest extends TestCase
{
    use RefreshDatabase;

    private $futureActivatedTitle;
    private $activeTitle;
    private $inactiveTitle;
    private $retiredTitle;

    public function setUp(): void
    {
        parent::setUp();

        $this->futureActivatedTitle = Title::factory()->withFutureActivation()->create();
        $this->activeTitle = Title::factory()->active()->create();
        $this->inactiveTitle = Title::factory()->inactive()->create();
        $this->retiredTitle = Title::factory()->retired()->create();
    }

    /**
     * @test
     */
    public function it_can_get_future_activated_titles()
    {
        $futureActivatedTitles = Title::withFutureActivation()->get();

        $this->assertCount(1, $futureActivatedTitles);
        $this->assertTrue($futureActivatedTitles->contains($this->futureActivatedTitle));
        $this->assertFalse($futureActivatedTitles->contains($this->activeTitle));
        $this->assertFalse($futureActivatedTitles->contains($this->inactiveTitle));
        $this->assertFalse($futureActivatedTitles->contains($this->retiredTitle));
    }

    /**
     * @test
     */
    public function it_can_get_active_titles()
    {
        $activeTitles = Title::active()->get();

        $this->assertCount(1, $activeTitles);
        $this->assertTrue($activeTitles->contains($this->activeTitle));
        $this->assertFalse($activeTitles->contains($this->futureActivatedTitle));
        $this->assertFalse($activeTitles->contains($this->inactiveTitle));
        $this->assertFalse($activeTitles->contains($this->retiredTitle));
    }

    /**
     * @test
     */
    public function it_can_get_inactive_titles()
    {
        $inactiveTitles = Title::inactive()->get();

        $this->assertCount(1, $inactiveTitles);
        $this->assertTrue($inactiveTitles->contains($this->inactiveTitle));
        $this->assertFalse($inactiveTitles->contains($this->futureActivatedTitle));
        $this->assertFalse($inactiveTitles->contains($this->activeTitle));
        $this->assertFalse($inactiveTitles->contains($this->retiredTitle));
    }

    /**
     * @test
     */
    public function it_can_get_retired_titles()
    {
        $retiredTitles = Title::retired()->get();

        $this->assertCount(1, $retiredTitles);
        $this->assertTrue($retiredTitles->contains($this->retiredTitle));
        $this->assertFalse($retiredTitles->contains($this->futureActivatedTitle));
        $this->assertFalse($retiredTitles->contains($this->activeTitle));
        $this->assertFalse($retiredTitles->contains($this->inactiveTitle));
    }
}
