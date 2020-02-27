<?php

namespace Tests\Feature\SuperAdmin\TagTeams;

use App\Enums\Role;
use App\Models\TagTeam;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Factories\WrestlerFactory;

/**
 * @group tagteams
 * @group superadmins
 * @group roster
 */
class CreateTagTeamSuccessConditionsTest extends TestCase
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
        $wrestlers = WrestlerFactory::new()->count(2)->bookable()->create();

        return array_replace_recursive([
            'name' => 'Example Tag Team Name',
            'signature_move' => 'The Finisher',
            'started_at' => now()->toDateTimeString(),
            'wrestlers' => $overrides['wrestlers'] ?? $wrestlers->modelKeys(),
        ], $overrides);
    }

    /** @test */
    public function a_super_administrator_can_view_the_form_for_creating_a_tag_team()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);

        $response = $this->createRequest('tag-team');

        $response->assertViewIs('tagteams.create');
        $response->assertViewHas('tagTeam', new TagTeam);
    }

    /** @test */
    public function a_super_administrator_can_create_a_tag_team()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);

        $response = $this->storeRequest('tag-team', $this->validParams());

        $response->assertRedirect(route('tag-teams.index'));
        tap(TagTeam::first(), function ($tagTeam) {
            $this->assertEquals('Example Tag Team Name', $tagTeam->name);
            $this->assertEquals('The Finisher', $tagTeam->signature_move);
        });
    }

    /** @test */
    public function a_super_administrator_can_employ_a_tag_team_during_creation()
    {
        $now = now();
        Carbon::setTestNow($now);

        $this->actAs(Role::SUPER_ADMINISTRATOR);

        $this->storeRequest('tag-team', $this->validParams(['started_at' => $now->toDateTimeString()]));

        tap(TagTeam::first(), function ($tagteam) use ($now) {
            $this->assertDatabaseHas('employments', [
                'employable_id' => $tagteam->id,
                'employable_type' => get_class($tagteam),
                'started_at' => $now->toDateTimeString(),
            ]);
        });
    }

    /** @test */
    public function a_super_administrator_can_create_a_tag_team_without_employing()
    {
        $now = now();
        Carbon::setTestNow($now);

        $this->actAs(Role::SUPER_ADMINISTRATOR);

        $this->storeRequest('tag-team', $this->validParams(['started_at' => null]));

        tap(TagTeam::first(), function ($tagteam) use ($now) {
            $this->assertDatabaseMissing('employments', [
                'employable_id' => $tagteam->id,
                'employable_type' => get_class($tagteam),
                'started_at' => $now->toDateTimeString(),
            ]);
        });
    }
}
