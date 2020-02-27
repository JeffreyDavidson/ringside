<?php

namespace Tests\Feature\User\TagTeams;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Factories\WrestlerFactory;

/**
 * @group tagteams
 * @group users
 * @group roster
 */
class CreateTagTeamFailureConditionsTest extends TestCase
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
        $wrestlers = WrestlerFactory::new()->count(2)->create();

        return array_replace_recursive([
            'name' => 'Example Tag Team Name',
            'signature_move' => 'The Finisher',
            'started_at' => now()->toDateTimeString(),
            'wrestlers' => $overrides['wrestlers'] ?? $wrestlers->modelKeys(),
        ], $overrides);
    }

    /** @test */
    public function a_basic_user_cannot_view_the_form_for_creating_a_tag_team()
    {
        $this->actAs(Role::BASIC);

        $response = $this->createRequest('tag-teams');

        $response->assertForbidden();
    }

    /** @test */
    public function a_basic_user_cannot_create_a_tag_team()
    {
        $this->actAs(Role::BASIC);

        $response = $this->storeRequest('tag-team', $this->validParams());

        $response->assertForbidden();
    }
}
