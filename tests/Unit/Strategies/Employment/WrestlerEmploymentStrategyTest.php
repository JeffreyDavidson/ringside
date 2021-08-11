<?php

namespace Tests\Unit\Strategies\Employment;

use App\Exceptions\CannotBeEmployedException;
use App\Models\Wrestler;
use App\Strategies\Employment\WrestlerEmploymentStrategy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WrestlerEmploymentStrategyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function employing_a_bookable_wrestler_throws_an_exception()
    {
        $this->expectException(CannotBeEmployedException::class);
        $this->withoutExceptionHandling();

        $wrestler = Wrestler::factory()->bookable()->create();

        (new WrestlerEmploymentStrategy($wrestler))->employ();
    }
}
