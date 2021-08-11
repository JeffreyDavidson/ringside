<?php

namespace Tests\Integration\Models;

use App\Models\Title;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group titles
 */
class TitleIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_can_get_active_titles()
    {
        $activeTitle = Title::factory()->active()->create();
        $retiredTitle = Title::factory()->retired()->create();

        $activeTitles = Title::active()->get();

        $this->assertCount(1, $activeTitles);
        $this->assertTrue($activeTitles->contains($activeTitle));
        $this->assertFalse($activeTitles->contains($retiredTitle));
    }

    /**
     * @test
     */
    public function it_can_get_retired_titles()
    {
        $activeTitle = Title::factory()->active()->create();
        $retiredTitle = Title::factory()->retired()->create();

        $retiredTitles = Title::retired()->get();

        $this->assertCount(1, $retiredTitles);
        $this->assertTrue($retiredTitles->contains($retiredTitle));
        $this->assertFalse($retiredTitles->contains($activeTitle));
    }
}
