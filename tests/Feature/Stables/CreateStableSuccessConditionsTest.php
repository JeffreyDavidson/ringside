<?php

namespace Tests\Feature\Admin\Stables;

use App\Enums\Role;
use App\Models\Stable;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\TagTeamFactory;
use Tests\Factories\WrestlerFactory;
use Tests\TestCase;

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
        $wrestler = WrestlerFactory::new()->count(1)->bookable()->create();
        $tagTeam = TagTeamFactory::new()->count(1)->bookable()->create();

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
        $this->actAs(Role::ADMINISTRATOR);

        $response = $this->createRequest('stable');

        $response->assertViewIs('stables.create');
    }

    /** @test */
    public function an_administrator_can_create_a_stable()
    {
        $now = now();
        Carbon::setTestNow($now);

        $this->actAs(Role::ADMINISTRATOR);

        $response = $this->storeRequest('stable', $this->validParams());

        $response->assertRedirect(route('stables.index'));
        tap(Stable::first(), function ($stable) use ($now) {
            $this->assertEquals('Example Stable Name', $stable->name);
            $this->assertEquals($now->toDateTimeString(), $stable->currentEmployment->started_at->toDateTimeString());
        });
    }
}
