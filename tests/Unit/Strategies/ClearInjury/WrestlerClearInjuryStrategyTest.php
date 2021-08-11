<?php

namespace Tests\Unit\Strategies\ClearInjury;

use App\Models\Wrestler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class WrestlerClearInjuryStrategyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function clearing_an_injury_from_an_unemployed_wrestler_throws_an_exception()
    {
        $this->expectException(CannotBeClearedFromInjuryException::class);
        $this->withoutExceptionHandling();

        $wrestler = Wrestler::factory()->create();

        $wrestler->clearFromInjury();
    }
}
