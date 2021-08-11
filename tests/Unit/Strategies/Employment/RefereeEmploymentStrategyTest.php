<?php

namespace Tests\Unit\Strategies\Employment;

use App\Exceptions\CannotBeEmployedException;
use App\Models\Referee;
use App\Strategies\Employment\RefereeEmploymentStrategy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RefereeEmploymentStrategyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function employing_an_injured_referee_throws_an_exception()
    {
        $this->expectException(CannotBeEmployedException::class);
        $this->withoutExceptionHandling();

        $referee = Referee::factory()->injured()->create();

        (new RefereeEmploymentStrategy($referee))->employ();
    }
}
