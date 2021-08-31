<?php

namespace Tests\Feature\Http\Controllers\TagTeams;

use App\Enums\Role;
use App\Http\Controllers\TagTeams\TagTeamsController;
use App\Http\Requests\TagTeams\StoreRequest;
use App\Http\Requests\TagTeams\UpdateRequest;
use App\Models\TagTeam;
use App\Models\User;
use App\Models\Wrestler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\TagTeamRequestDataFactory;
use Tests\TestCase;

/**
 * @group tagteams
 * @group feature-tagteams
 * @group roster
 * @group feature-roster
 */
class TagTeamControllerTest extends TestCase
{
    use RefreshDatabase;

    private TagTeam $tagTeam;
    private TagTeamRequestDataFactory $factory;

    public function setUp(): void
    {
        parent::setUp();

        $this->tagTeam = TagTeam::factory()->create();
        $this->factory = TagTeamRequestDataFactory::new()->withTagTeam($this->tagTeam);
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function index_returns_a_view($administrators)
    {
        $this->withoutExceptionHandling();
        $this
            ->actAs($administrators)
            ->get(action([TagTeamsController::class, 'index']))
            ->assertOk()
            ->assertViewIs('tagteams.index')
            ->assertSeeLivewire('tag-teams.employed-tag-teams')
            ->assertSeeLivewire('tag-teams.future-employed-and-unemployed-tag-teams')
            ->assertSeeLivewire('tag-teams.released-tag-teams')
            ->assertSeeLivewire('tag-teams.suspended-tag-teams')
            ->assertSeeLivewire('tag-teams.retired-tag-teams');
    }

    /**
     * @test
     */
    public function a_basic_user_cannot_view_tag_teams_index_page()
    {
        $this
            ->actAs(Role::BASIC)
            ->get(action([TagTeamsController::class, 'index']))
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function a_guest_cannot_view_tag_teams_index_page()
    {
        $this
            ->get(action([TagTeamsController::class, 'index']))
            ->assertRedirect(route('login'));
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function create_returns_a_view($administrators)
    {
        $this
            ->actAs($administrators)
            ->get(action([TagTeamsController::class, 'create']))
            ->assertViewIs('tagteams.create')
            ->assertViewHas('tagTeam', new TagTeam);
    }

    /**
     * @test
     */
    public function a_basic_user_cannot_view_the_form_for_creating_a_tag_team()
    {
        $this
            ->actAs(Role::BASIC)
            ->get(action([TagTeamsController::class, 'create']))
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function a_guest_cannot_view_the_form_for_creating_a_tag_team()
    {
        $this
            ->get(action([TagTeamsController::class, 'create']))
            ->assertRedirect(route('login'));
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function store_creates_a_tag_team_and_redirects($administrators)
    {
        $this
            ->actAs($administrators)
            ->from(action([TagTeamsController::class, 'create']))
            ->post(action([TagTeamsController::class, 'store']), TagTeamRequestDataFactory::new()->create())
            ->assertRedirect(route('tag-teams.index'));

        tap(TagTeam::all()->last(), function ($tagTeam) {
            $this->assertEquals('Example Tag Team Name', $tagTeam->name);
            $this->assertEquals('The Signature Move', $tagTeam->signature_move);
        });
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function an_employment_is_not_created_for_the_tag_team_if_started_at_is_filled_in_request($administrators)
    {
        $this
            ->actAs($administrators)
            ->from(action([TagTeamsController::class, 'create']))
            ->post(
                action([TagTeamsController::class, 'store']),
                TagTeamRequestDataFactory::new()->create(['started_at' => null])
            );

        tap(TagTeam::all()->last(), function ($tagTeam) {
            $this->assertCount(0, $tagTeam->employments);
        });
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function an_employment_is_created_for_the_tag_team_if_started_at_is_filled_in_request($administrators)
    {
        $startedAt = now()->toDateTimeString();

        $this
            ->actAs($administrators)
            ->from(action([TagTeamsController::class, 'create']))
            ->post(
                action([TagTeamsController::class, 'store']),
                TagTeamRequestDataFactory::new()->create(['started_at' => $startedAt])
            );

        tap(TagTeam::all()->last(), function ($tagTeam) use ($startedAt) {
            $this->assertCount(1, $tagTeam->employments);
            $this->assertEquals($startedAt, $tagTeam->employments->first()->started_at->toDateTimeString());
        });
    }

    /**
     * @test
     */
    public function a_basic_user_cannot_create_a_tag_team()
    {
        $this
            ->actAs(Role::BASIC)
            ->from(action([TagTeamsController::class, 'create']))
            ->post(action([TagTeamsController::class, 'store']), TagTeamRequestDataFactory::new()->create())
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function a_guest_cannot_create_a_tag_team()
    {
        $this
            ->from(action([TagTeamsController::class, 'create']))
            ->post(action([TagTeamsController::class, 'store']), TagTeamRequestDataFactory::new()->create())
            ->assertRedirect(route('login'));
    }

    /**
     * @test
     */
    public function store_validates_using_a_form_request()
    {
        $this->assertActionUsesFormRequest(TagTeamsController::class, 'store', StoreRequest::class);
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function show_returns_a_view($administrators)
    {
        $this
            ->actAs($administrators)
            ->get(action([TagTeamsController::class, 'show'], $this->tagTeam))
            ->assertViewIs('tagteams.show')
            ->assertViewHas('tagTeam', $this->tagTeam);
    }

    /**
     * @test
     */
    public function a_basic_user_can_view_their_tag_team_profile()
    {
        $this->actAs(Role::BASIC);
        $this->tagTeam = TagTeam::factory()->create(['user_id' => auth()->user()]);

        $this
            ->get(action([TagTeamsController::class, 'show'], $this->tagTeam))
            ->assertOk();
    }

    /**
     * @test
     */
    public function a_basic_user_cannot_view_another_users_tag_team_profile()
    {
        $this->tagTeam = TagTeam::factory()->create(['user_id' => User::factory()->create()->id]);

        $this
            ->actAs(Role::BASIC)
            ->get(action([TagTeamsController::class, 'index'], $this->tagTeam))
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function a_guest_cannot_view_a_tag_team_profile()
    {
        $this
            ->get(action([TagTeamsController::class, 'show'], $this->tagTeam))
            ->assertRedirect(route('login'));
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function edit_returns_a_view($administrators)
    {
        $this
            ->actAs($administrators)
            ->get(action([TagTeamsController::class, 'edit'], $this->tagTeam))
            ->assertViewIs('tagteams.edit')
            ->assertViewHas('tagTeam', $this->tagTeam);
    }

    /**
     * @test
     */
    public function a_basic_user_cannot_view_the_form_for_editing_a_tag_team()
    {
        $this
            ->actAs(Role::BASIC)
            ->get(action([TagTeamsController::class, 'edit'], $this->tagTeam))
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function a_guest_cannot_view_the_form_for_editing_a_tag_team()
    {
        $this
            ->get(action([TagTeamsController::class, 'edit'], $this->tagTeam))
            ->assertRedirect(route('login'));
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function updates_a_tag_team_and_redirects($administrators)
    {
        $this
            ->actAs($administrators)
            ->from(action([TagTeamsController::class, 'edit'], $this->tagTeam))
            ->put(action([TagTeamsController::class, 'update'], $this->tagTeam), $this->factory->create())
            ->assertRedirect(action([TagTeamsController::class, 'index']));

        tap($this->tagTeam->fresh(), function ($tagTeam) {
            $this->assertEquals('Example Tag Team Name', $tagTeam->name);
            $this->assertEquals('The Signature Move', $tagTeam->signature_move);
        });
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function wrestlers_of_tag_team_are_synced_when_tag_team_is_updated($administrators)
    {
        $this->tagTeam = TagTeam::factory()->bookable()->create();

        $this->assertTrue($this->tagTeam->isCurrentlyEmployed());

        $formerTagTeamPartners = $this->tagTeam->currentWrestlers;

        $newTagTeamPartners = Wrestler::factory()->count(2)->bookable()->create();

        $this->assertCount(6, Wrestler::all());

        $this
            ->actAs($administrators)
            ->from(action([TagTeamsController::class, 'edit'], $this->tagTeam))
            ->put(
                action([TagTeamsController::class, 'update'], $this->tagTeam),
                $this->factory->create(['wrestlers' => $newTagTeamPartners->pluck('id')->toArray()])
            )
            ->assertRedirect(action([TagTeamsController::class, 'index']));

        tap($this->tagTeam->fresh(), function ($tagTeam) use ($formerTagTeamPartners, $newTagTeamPartners) {
            $this->assertCount(4, $tagTeam->wrestlers);
            $this->assertCount(2, $tagTeam->currentWrestlers);
            $this->assertCollectionHas($tagTeam->currentWrestlers, $newTagTeamPartners[0]);
            $this->assertCollectionHas($tagTeam->currentWrestlers, $newTagTeamPartners[1]);
            $this->assertCollectionDoesntHave($tagTeam->currentWrestlers, $formerTagTeamPartners[0]);
            $this->assertCollectionDoesntHave($tagTeam->currentWrestlers, $formerTagTeamPartners[1]);
        });
    }

    /**
     * @test
     */
    public function a_basic_user_cannot_update_a_tag_team()
    {
        $this
            ->actAs(Role::BASIC)
            ->from(action([TagTeamsController::class, 'edit'], $this->tagTeam))
            ->put(action([TagTeamsController::class, 'update'], $this->tagTeam), $this->factory->create())
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function a_guest_cannot_update_a_tag_team()
    {
        $this
            ->from(action([TagTeamsController::class, 'edit'], $this->tagTeam))
            ->put(action([TagTeamsController::class, 'update'], $this->tagTeam), $this->factory->create())
            ->assertRedirect(route('login'));
    }

    /**
     * @test
     */
    public function update_validates_using_a_form_request()
    {
        $this->assertActionUsesFormRequest(TagTeamsController::class, 'update', UpdateRequest::class);
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function deletes_a_tag_team_and_redirects($administrators)
    {
        $this->actAs($administrators)
            ->delete(action([TagTeamsController::class, 'destroy'], $this->tagTeam))
            ->assertRedirect(action([TagTeamsController::class, 'index']));

        $this->assertSoftDeleted($this->tagTeam);
    }

    /**
     * @test
     */
    public function a_basic_user_cannot_delete_a_tag_team()
    {
        $this->actAs(Role::BASIC)
            ->delete(action([TagTeamsController::class, 'destroy'], $this->tagTeam))
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function a_guest_cannot_delete_a_tag_team()
    {
        $this->delete(action([TagTeamsController::class, 'destroy'], $this->tagTeam))
            ->assertRedirect(route('login'));
    }
}
