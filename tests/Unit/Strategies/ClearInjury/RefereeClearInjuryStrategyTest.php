<?php

namespace Tests\Unit\Strategies\ClearInjury;

use App\Models\Referee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class RefereeClearInjuryStrategyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function clearing_an_injury_from_an_unemployed_referee_throws_an_exception()
    {
        $this->expectException(CannotBeClearedFromInjuryException::class);
        $this->withoutExceptionHandling();

        $referee = Referee::factory()->create();

        $referee->clearFromInjury();
    }
}
