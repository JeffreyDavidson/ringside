<?php

namespace Tests\Feature\Admin\Stables;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Stable;
use App\Models\TagTeam;
use App\Models\Wrestler;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group stables
 * @group admins
 * @group roster
 */
class CreateStableSuccessConditionsTest extends TestCase
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
        $tagTeam = factory(TagTeam::class)->states('bookable')->create();

        return array_replace([
            'name' => 'Example Stable Name',
            'started_at' => now()->toDateTimeString(),
            'wrestlers' => [$wrestler->getKey()],
            'tagteams' => [$tagTeam->getKey()],
        ], $overrides);
    }

    /** @test */
    public function an_administrator_can_view_the_form_for_creating_a_stable()
    {
        $this->actAs('administrator');

        $response = $this->createRequest('stable');

        $response->assertViewIs('stables.create');
    }

    /** @test */
    public function an_administrator_can_create_a_stable()
    {
        $now = now();
        Carbon::setTestNow($now);

        $this->actAs('administrator');

        $response = $this->storeRequest('stable', $this->validParams());

        $response->assertRedirect(route('stables.index'));
        tap(Stable::first(), function ($stable) use ($now) {
            $this->assertEquals('Example Stable Name', $stable->name);
            $this->assertEquals($now->toDateTimeString(), $stable->employment->started_at->toDateTimeString());
        });
    }
}
