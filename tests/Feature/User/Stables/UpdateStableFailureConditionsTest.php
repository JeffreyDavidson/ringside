<?php

namespace Tests\Feature\User\Stables;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\StableFactory;
use Tests\Factories\TagTeamFactory;
use Tests\Factories\WrestlerFactory;
use Tests\TestCase;

/**
 * @group stables
 * @group users
 * @group roster
 */
class UpdateStableFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Valid Parameters for request.
     *
     * @param  array $overrides
     * @return array
     */
    private function validParams($overrides = [])
    {
        $wrestlers = WrestlerFactory::new()->count(1)->bookable()->create();
        $tagTeams = TagTeamFactory::new()->count(1)->bookable()->create();

        return array_replace([
            'name' => 'Example Stable Name',
            'started_at' => now()->toDateTimeString(),
            'wrestlers' => $overrides['wrestlers'] ?? $wrestlers->modelKeys(),
            'tagteams' => $overrides['tagteams'] ?? $tagTeams->modelKeys(),
        ], $overrides);
    }

    /** @test */
    public function a_basic_user_cannot_view_the_form_for_editing_a_stable()
    {
        $this->actAs(Role::BASIC);
        $stable = StableFactory::new()->create();

        $response = $this->editRequest($stable);

        $response->assertForbidden();
    }

    /** @test */
    public function a_basic_user_cannot_update_a_stable()
    {
        $this->actAs(Role::BASIC);
        $stable = StableFactory::new()->create();

        $response = $this->updateRequest($stable, $this->validParams());

        $response->assertForbidden();
    }
}
