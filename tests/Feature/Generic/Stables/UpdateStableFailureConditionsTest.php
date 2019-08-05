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
class UpdateStableFailureConditionsTest extends TestCase
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
    public function a_stable_name_must_be_filled()
    {
        $this->actAs('administrator');
        $stable = factory(Stable::class)->create();

        $response = $this->put(route('roster.stables.update', $stable), $this->validParams([
            'name' => ''
        ]));

        $response->assertStatus(302);
        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function a_stable_name_must_be_unique()
    {
        $this->actAs('administrator');
        factory(Stable::class)->create(['name' => 'Example Stable Name']);
        $stableB = factory(Stable::class)->create();

        $response = $this->put(route('roster.stables.update', $stableB), $this->validParams([
            'name' => 'Example Stable Name'
        ]));

        $response->assertStatus(302);
        $response->assertSessionHasErrors('name');
    }
}
