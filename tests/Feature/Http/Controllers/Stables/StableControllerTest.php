<?php

namespace Tests\Feature\Http\Controllers\Stables;

use App\Enums\Role;
use App\Http\Controllers\Stables\StablesController;
use App\Http\Requests\Stables\StoreRequest;
use App\Http\Requests\Stables\UpdateRequest;
use App\Models\Stable;
use App\Models\TagTeam;
use App\Models\User;
use App\Models\Wrestler;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\StableRequestDataFactory;
use Tests\TestCase;

/**
 * @group stables
 * @group feature-stables
 * @group roster
 * @group feature-roster
 */
class StableControllerTest extends TestCase
{
    use RefreshDatabase;

    private Stable $stable;
    private StableRequestDataFactory $factory;

    public function setUp(): void
    {
        parent::setUp();

        $this->stable = Stable::factory()->create();
        $this->factory = StableRequestDataFactory::new()->withStable($this->stable);
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function index_returns_a_view($administrators)
    {
        $this
            ->actAs($administrators)
            ->get(action([StablesController::class, 'index']))
            ->assertOk()
            ->assertViewIs('stables.index')
            ->assertSeeLivewire('stables.active-stables')
            ->assertSeeLivewire('stables.future-activation-and-unactivated-stables')
            ->assertSeeLivewire('stables.inactive-stables')
            ->assertSeeLivewire('stables.retired-stables');
    }

    /**
     * @test
     */
    public function a_basic_user_cannot_view_stables_index_page()
    {
        $this
            ->actAs(Role::BASIC)
            ->get(action([StablesController::class, 'index']))
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function a_guest_cannot_view_stables_index_page()
    {
        $this
            ->get(action([StablesController::class, 'index']))
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
            ->get(action([StablesController::class, 'create']))
            ->assertViewIs('stables.create')
            ->assertViewHas('stable', new Stable);
    }

    /**
     * @test
     */
    public function a_basic_user_cannot_view_the_form_for_creating_a_stable()
    {
        $this
            ->actAs(Role::BASIC)
            ->get(action([StablesController::class, 'create']))
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function a_guest_cannot_view_the_form_for_creating_a_stable()
    {
        $this
            ->get(action([StablesController::class, 'create']))
            ->assertRedirect(route('login'));
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function store_creates_a_stable_and_redirects($administrators)
    {
        $this
            ->actAs($administrators)
            ->from(action([StablesController::class, 'create']))
            ->post(action([StablesController::class, 'store']), StableRequestDataFactory::new()->create())
            ->assertRedirect(action([StablesController::class, 'index']));

        tap(Stable::all()->last(), function ($stable) {
            $this->assertEquals('Example Stable Name', $stable->name);
        });
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function an_activation_is_not_created_for_the_stable_if_started_at_is_not_filled_in_request($administrators)
    {
        $this
            ->actAs($administrators)
            ->from(action([StablesController::class, 'create']))
            ->post(
                action([StablesController::class, 'store']),
                StableRequestDataFactory::new()->create(['started_at' => null])
            );

        tap(Stable::all()->last(), function ($stable) {
            $this->assertCount(0, $stable->activations);
        });
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function an_activation_is_created_for_the_stable_if_started_at_is_filled_in_request($administrators)
    {
        $startedAt = now()->toDateTimeString();

        $this
            ->actAs($administrators)
            ->from(action([StablesController::class, 'create']))
            ->post(
                action([StablesController::class, 'store']),
                StableRequestDataFactory::new()->create(['started_at' => $startedAt])
            );

        tap(Stable::all()->first(), function ($stable) use ($startedAt) {
            $this->assertCount(1, $stable->activations);
            $this->assertEquals($startedAt, $stable->activations->first()->started_at->toDateTimeString());
        });
    }

    /**
     * @test
     */
    public function wrestlers_are_added_to_stable_if_present()
    {
        $createdWrestlers = Wrestler::factory()->count(3)->bookable()->create();

        $this
            ->actAs(Role::ADMINISTRATOR)
            ->from(action([StablesController::class, 'create']))
            ->post(
                action([StablesController::class, 'store']),
                StableRequestDataFactory::new()->withWrestlers($createdWrestlers)->create()
            );

        tap(Stable::first()->currentWrestlers, function ($wrestlers) use ($createdWrestlers) {
            $this->assertCount(3, $wrestlers);
            $this->assertEquals($wrestlers->modelKeys(), $createdWrestlers->modelKeys());
        });
    }

    /**
     * @test
     */
    public function tag_teams_are_added_to_stable_if_present()
    {
        $tagTeam = TagTeam::factory()->bookable()->create();

        $this
            ->actAs(Role::ADMINISTRATOR)
            ->from(action([StablesController::class, 'create']))
            ->post(
                action([StablesController::class, 'store']),
                StableRequestDataFactory::new()->withTagTeams([$tagTeam])->create()
            );

        tap(Stable::first()->currentTagTeams, function ($tagTeams) use ($tagTeam) {
            $this->assertCount(1, $tagTeams);
            $this->assertTrue($tagTeams->contains($tagTeam));
        });
    }

    /**
     * @test
     */
    public function a_stables_members_join_when_stable_is_started_if_filled()
    {
        $this
            ->actAs(Role::ADMINISTRATOR)
            ->from(action([StablesController::class, 'create']))
            ->post(
                action([StablesController::class, 'store']),
                StableRequestDataFactory::new()->withStartDate(now()->toDateTimeString())->create()
            );

        tap(Stable::first(), function ($stable) {
            $wrestlers = $stable->currentWrestlers;
            $tagTeams = $stable->currentTagTeams;
            foreach ($wrestlers as $wrestler) {
                $this->assertNotNull($wrestler->pivot->joined_at);
            }

            foreach ($tagTeams as $tagTeam) {
                $this->assertNotNull($tagTeam->pivot->joined_at);
            }
        });
    }

    /**
     * @test
     */
    public function a_stables_members_join_at_the_current_time_when_stable_is_created_if_started_at_is_not_filled()
    {
        $wrestler = Wrestler::factory()->create();
        $tagTeam = TagTeam::factory()->create();
        $now = now()->toDateTimeString();
        Carbon::setTestNow($now);

        $this
            ->actAs(Role::ADMINISTRATOR)
            ->from(action([StablesController::class, 'create']))
            ->post(
                action([StablesController::class, 'store']),
                StableRequestDataFactory::new()->withWrestlers([$wrestler])->withTagTeams([$tagTeam])->create([
                    'started_at' => '',
                ])
            );

        tap(Stable::first(), function ($stable) use ($wrestler, $tagTeam, $now) {
            $wrestlers = $stable->currentWrestlers;
            $tagTeams = $stable->currentTagTeams;

            $this->assertCollectionHas($wrestlers, $wrestler);
            $this->assertCollectionHas($tagTeams, $tagTeam);

            $this->assertEquals($now, $wrestlers->first()->pivot->joined_at->toDateTimeString());
            $this->assertEquals($now, $tagTeams->first()->pivot->joined_at->toDateTimeString());
        });
    }

    /**
     * @test
     */
    public function a_basic_user_cannot_create_a_stable()
    {
        $this
            ->actAs(Role::BASIC)
            ->from(action([StablesController::class, 'create']))
            ->post(action([StablesController::class, 'store']), StableRequestDataFactory::new()->create())
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function a_guest_cannot_create_a_stable()
    {
        $this
            ->from(action([StablesController::class, 'create']))
            ->post(action([StablesController::class, 'store']), StableRequestDataFactory::new()->create())
            ->assertRedirect(route('login'));
    }

    /**
     * @test
     */
    public function store_validates_using_a_form_request()
    {
        $this->assertActionUsesFormRequest(StablesController::class, 'store', StoreRequest::class);
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function show_returns_a_view($administrators)
    {
        $this
            ->actAs($administrators)
            ->get(action([StablesController::class, 'show'], $this->stable))
            ->assertViewIs('stables.show')
            ->assertViewHas('stable', $this->stable);
    }

    /**
     * @test
     */
    public function a_basic_user_can_view_their_stable_profile()
    {
        $this->actAs(Role::BASIC);
        $this->stable = Stable::factory()->create(['user_id' => auth()->user()]);

        $this
            ->get(action([StablesController::class, 'show'], $this->stable))
            ->assertOk();
    }

    /**
     * @test
     */
    public function a_basic_user_cannot_view_another_users_stable_profile()
    {
        $otherUser = User::factory()->create();
        $this->stable = Stable::factory()->create(['user_id' => $otherUser->id]);

        $this
            ->actAs(Role::BASIC)
            ->get(action([StablesController::class, 'show'], $this->stable))
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function a_guest_cannot_view_a_stable_profile()
    {
        $this
            ->get(action([StablesController::class, 'show'], $this->stable))
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
            ->get(action([StablesController::class, 'edit'], $this->stable))
            ->assertViewIs('stables.edit')
            ->assertViewHas('stable', $this->stable);
    }

    /**
     * @test
     */
    public function a_basic_user_cannot_view_the_form_for_editing_a_stable()
    {
        $this
            ->actAs(Role::BASIC)
            ->get(action([StablesController::class, 'edit'], $this->stable))
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function a_guest_cannot_view_the_form_for_editing_a_stable()
    {
        $this
            ->get(action([StablesController::class, 'edit'], $this->stable))
            ->assertRedirect(route('login'));
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function updates_a_stable_and_redirects($administrators)
    {
        $this
            ->actAs($administrators)
            ->from(action([StablesController::class, 'edit'], $this->stable))
            ->put(
                action([StablesController::class, 'update'], $this->stable),
                StableRequestDataFactory::new()->withStable($this->stable)->create()
            )
            ->assertRedirect(action([StablesController::class, 'index']));

        tap($this->stable->fresh(), function ($stable) {
            $this->assertEquals('Example Stable Name', $stable->name);
        });
    }

    public function wrestlers_of_stable_are_synced_when_stable_is_updated()
    {
        $this->stable = Stable::factory()->active()->create();
        $wrestlers = Wrestler::factory()->bookable()->times(2)->create();

        $this
            ->actAs(Role::ADMINISTRATOR)
            ->from(action([StablesController::class, 'edit'], $this->stable))
            ->put(
                action([StablesController::class, 'update'], $this->stable),
                StableRequestDataFactory::new()->withStable($this->stable)->withWrestlers([$wrestlers])->create()
            )
            ->assertRedirect(action([StablesController::class, 'index']));

        tap($this->stable->currentWrestlers->fresh(), function ($stableWrestlers) use ($wrestlers) {
            $this->assertCount(2, $stableWrestlers);
            $this->assertEquals($stableWrestlers->modelKeys(), $wrestlers->modelKeys());
        });
    }

    /**
     * @test
     */
    public function a_basic_user_cannot_update_a_stable()
    {
        $this
            ->actAs(Role::BASIC)
            ->from(action([StablesController::class, 'edit'], $this->stable))
            ->put(
                action([StablesController::class, 'update'], $this->stable),
                StableRequestDataFactory::new()->withStable($this->stable)->create()
            )
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function a_guest_cannot_update_a_stable()
    {
        $this
            ->from(action([StablesController::class, 'edit'], $this->stable))
            ->put(
                action([StablesController::class, 'update'], $this->stable),
                StableRequestDataFactory::new()->withStable($this->stable)->create()
            )
            ->assertRedirect(route('login'));
    }

    /**
     * @test
     */
    public function update_validates_using_a_form_request()
    {
        $this->assertActionUsesFormRequest(StablesController::class, 'update', UpdateRequest::class);
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function deletes_a_stable_and_redirects($administrators)
    {
        $this
            ->actAs($administrators)
            ->delete(action([StablesController::class, 'destroy'], $this->stable))
            ->assertRedirect(action([StablesController::class, 'index']));

        $this->assertSoftDeleted($this->stable);
    }

    /**
     * @test
     */
    public function a_basic_user_cannot_delete_a_stable()
    {
        $this
            ->actAs(Role::BASIC)
            ->delete(action([StablesController::class, 'destroy'], $this->stable))
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function a_guest_cannot_delete_a_stable()
    {
        $this
            ->delete(action([StablesController::class, 'destroy'], $this->stable))
            ->assertRedirect(route('login'));
    }
}
