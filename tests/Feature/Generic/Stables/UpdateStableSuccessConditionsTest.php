<?php

namespace Tests\Feature\Generic\Stables;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Stable;
use App\Models\TagTeam;
use App\Models\Wrestler;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group stables
 * @group generics
 */
class UpdateStableSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Valid parameters for request.
     *
     * @param  array $overrides
     * @return array
     */
    private function validParams($overrides = [])
    {
        $wrestler = factory(Wrestler::class)->states('bookable')->create();
        $tagteam = factory(TagTeam::class)->states('bookable')->create();

        return array_replace_recursive([
            'name' => 'Example Stable Name',
            'started_at' => now()->toDateTimeString(),
            'wrestlers' => [$wrestler->getKey()],
            'tagteams' => [$tagteam->getKey()],
        ], $overrides);
    }

    /** @test */
    public function wrestlers_can_rejoin_a_stable()
    {
        $now = now()->subDays(3);
        Carbon::setTestNow($now);

        $this->actAs('administrator');
        $stable = factory(Stable::class)->create();
        $wrestler = factory(Wrestler::class)->states('bookable')->create();
        $stable->wrestlerHistory()->attach($wrestler->getKey(), ['left_at' => now()]);

        $this->from(route('roster.stables.edit', $stable))
            ->put(route('roster.stables.update', [$stable]), [
                'wrestlers' => [$wrestler->getKey()]
            ]);

        tap($stable->fresh()->currentWrestlers, function ($wrestlers) use ($wrestler) {
            $this->assertCount(1, $wrestlers);
            $this->assertEquals($wrestlers->first()->id, $wrestler->id);
        });
    }
}
