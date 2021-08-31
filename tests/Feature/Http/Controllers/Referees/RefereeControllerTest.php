<?php

namespace Tests\Feature\Http\Controllers\Referees;

use App\Enums\Role;
use App\Http\Controllers\Referees\RefereesController;
use App\Http\Requests\Referees\StoreRequest;
use App\Http\Requests\Referees\UpdateRequest;
use App\Models\Referee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\RefereeRequestDataFactory;
use Tests\TestCase;

/**
 * @group referees
 * @group feature-referees
 * @group roster
 * @group feature-roster
 */
class RefereeControllerTest extends TestCase
{
    use RefreshDatabase;

    private Referee $referee;
    private RefereeRequestDataFactory $factory;

    public function setUp(): void
    {
        parent::setUp();

        $this->referee = Referee::factory()->create();
        $this->factory = RefereeRequestDataFactory::new()->withReferee($this->referee);
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function index_returns_a_view($administrators)
    {
        $this
            ->actAs($administrators)
            ->get(action([RefereesController::class, 'index']))
            ->assertOk()
            ->assertViewIs('referees.index')
            ->assertSeeLivewire('referees.employed-referees')
            ->assertSeeLivewire('referees.future-employed-and-unemployed-referees')
            ->assertSeeLivewire('referees.released-referees')
            ->assertSeeLivewire('referees.suspended-referees')
            ->assertSeeLivewire('referees.injured-referees')
            ->assertSeeLivewire('referees.retired-referees');
    }

    /**
     * @test
     */
    public function a_basic_user_cannot_view_referees_index_page()
    {
        $this
            ->actAs(Role::BASIC)
            ->get(action([RefereesController::class, 'index']))
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function a_guest_cannot_view_referees_index_page()
    {
        $this
            ->get(action([RefereesController::class, 'index']))
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
            ->get(action([RefereesController::class, 'create']))
            ->assertViewIs('referees.create')
            ->assertViewHas('referee', new Referee);
    }

    /**
     * @test
     */
    public function a_basic_user_cannot_view_the_form_for_creating_a_referee()
    {
        $this
            ->actAs(Role::BASIC)
            ->get(action([RefereesController::class, 'create']))
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function a_guest_cannot_view_the_form_for_creating_a_referee()
    {
        $this
            ->get(action([RefereesController::class, 'create']))
            ->assertRedirect(route('login'));
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function store_creates_a_referee_and_redirects($administrators)
    {
        $this
            ->actAs($administrators)
            ->from(action([RefereesController::class, 'create']))
            ->post(action([RefereesController::class, 'store'], RefereeRequestDataFactory::new()->create()))
            ->assertRedirect(action([RefereesController::class, 'index']));

        tap(Referee::all()->last(), function ($referee) {
            $this->assertEquals('James', $referee->first_name);
            $this->assertEquals('Williams', $referee->last_name);
        });
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function an_employment_is_not_created_for_the_referee_if_started_at_is_filled_in_request($administrators)
    {
        $this
            ->actAs($administrators)
            ->from(action([RefereesController::class, 'create']))
            ->post(
                action([RefereesController::class, 'index']),
                RefereeRequestDataFactory::new()->create(['started_at' => null])
            );

        tap(Referee::first(), function ($referee) {
            $this->assertCount(0, $referee->employments);
        });
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function an_employment_is_created_for_the_referee_if_started_at_is_filled_in_request($administrators)
    {
        $startedAt = now()->toDateTimeString();

        $this
            ->actAs($administrators)
            ->from(action([RefereesController::class, 'create']))
            ->post(
                action([RefereesController::class, 'store']),
                RefereeRequestDataFactory::new()->create(['started_at' => $startedAt])
            );

        tap(Referee::all()->last(), function ($referee) use ($startedAt) {
            $this->assertCount(1, $referee->employments);
            $this->assertEquals($startedAt, $referee->employments->first()->started_at->toDateTimeString());
        });
    }

    /**
     * @test
     */
    public function a_basic_user_cannot_create_a_referee()
    {
        $this
            ->actAs(Role::BASIC)
            ->from(action([RefereesController::class, 'create']))
            ->post(action([RefereesController::class, 'store']), RefereeRequestDataFactory::new()->create())
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function a_guest_cannot_create_a_referee()
    {
        $this
            ->from(action([RefereesController::class, 'create']))
            ->post(action([RefereesController::class, 'store']), RefereeRequestDataFactory::new()->create())
            ->assertRedirect(route('login'));
    }

    /**
     * @test
     */
    public function store_validates_using_a_form_request()
    {
        $this->assertActionUsesFormRequest(RefereesController::class, 'store', StoreRequest::class);
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function show_returns_a_view($administrators)
    {
        $this
            ->actAs($administrators)
            ->get(action([RefereesController::class, 'show'], $this->referee))
            ->assertViewIs('referees.show')
            ->assertViewHas('referee', $this->referee);
    }

    /**
     * @test
     */
    public function a_basic_user_cannot_view_a_referee_profile()
    {
        $this
            ->actAs(Role::BASIC)
            ->get(action([RefereesController::class, 'show'], $this->referee))
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function a_guest_cannot_view_a_referee_profile()
    {
        $this
            ->get(action([RefereesController::class, 'show'], $this->referee))
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
            ->get(action([RefereesController::class, 'edit'], $this->referee))
            ->assertViewIs('referees.edit')
            ->assertViewHas('referee', $this->referee);
    }

    /**
     * @test
     */
    public function a_basic_user_cannot_view_the_form_for_editing_a_referee()
    {
        $this
            ->actAs(Role::BASIC)
            ->get(action([RefereesController::class, 'edit'], $this->referee))
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function a_guest_cannot_view_the_form_for_editing_a_referee()
    {
        $this
            ->get(action([RefereesController::class, 'edit'], $this->referee))
            ->assertRedirect(route('login'));
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function update_a_referee($administrators)
    {
        $this
            ->actAs($administrators)
            ->from(action([RefereesController::class, 'edit'], $this->referee))
            ->put(
                action([RefereesController::class, 'update'], $this->referee),
                RefereeRequestDataFactory::new()->withReferee($this->referee)->create()
            )
            ->assertRedirect(action([RefereesController::class, 'index']));

        tap($this->referee->fresh(), function ($referee) {
            $this->assertEquals('James', $referee->first_name);
            $this->assertEquals('Williams', $referee->last_name);
        });
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function update_can_employ_an_unemployed_referee_when_started_at_is_filled($administrators)
    {
        $now = now()->toDateTimeString();
        $this->referee = Referee::factory()->unemployed()->create();

        $this
            ->actAs($administrators)
            ->from(action([RefereesController::class, 'edit'], $this->referee))
            ->put(
                action([RefereesController::class, 'update'], $this->referee),
                RefereeRequestDataFactory::new()->withReferee($this->referee)->create(['started_at' => $now])
            )
            ->assertRedirect(action([RefereesController::class, 'index']));

        tap($this->referee->fresh(), function ($referee) use ($now) {
            $this->assertCount(1, $referee->employments);
            $this->assertEquals($now, $referee->employments->first()->started_at->toDateTimeString());
        });
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function update_can_employ_a_future_employed_referee_when_started_at_is_filled($administrators)
    {
        $now = now()->toDateTimeString();
        $this->referee = Referee::factory()->withFutureEmployment()->create();

        $this
            ->actAs($administrators)
            ->from(action([RefereesController::class, 'edit'], $this->referee))
            ->put(
                action([RefereesController::class, 'update'], $this->referee),
                RefereeRequestDataFactory::new()->withReferee($this->referee)->create(['started_at' => $now])
            )
            ->assertRedirect(action([RefereesController::class, 'index']));

        tap($this->referee->fresh(), function ($referee) use ($now) {
            $this->assertCount(1, $referee->employments);
            $this->assertEquals($now, $referee->employments()->first()->started_at->toDateTimeString());
        });
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function update_cannot_reemploy_a_released_referee($administrators)
    {
        $this->referee = Referee::factory()->released()->create();
        $startDate = $this->referee->employments->last()->started_at->toDateTimeString();

        $this
            ->actAs($administrators)
            ->from(action([RefereesController::class, 'edit'], $this->referee))
            ->put(
                action([RefereesController::class, 'update'], $this->referee),
                RefereeRequestDataFactory::new()->withReferee($this->referee)->create()
            )
            ->assertRedirect(action([RefereesController::class, 'index']));

        tap($this->referee->fresh(), function ($referee) use ($startDate) {
            $this->assertCount(1, $referee->employments);
            $this->assertSame($startDate, $referee->employments->last()->started_at->toDateTimeString());
        });
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function updating_cannot_employ_a_bookable_referee_when_started_at_is_filled($administrators)
    {
        $this->referee = Referee::factory()->bookable()->create();

        $this
            ->actAs($administrators)
            ->from(action([RefereesController::class, 'edit'], $this->referee))
            ->put(
                action([RefereesController::class, 'update'], $this->referee),
                RefereeRequestDataFactory::new()->withReferee($this->referee)->create([
                    'started_at' => $this->referee->employments()->first()->started_at->toDateTimeString(),
                ])
            )
            ->assertRedirect(action([RefereesController::class, 'index']));

        tap($this->referee->fresh(), function ($referee) {
            $this->assertCount(1, $referee->employments);
        });
    }

    /**
     * @test
     */
    public function a_basic_user_cannot_update_a_referee()
    {
        $this
            ->actAs(Role::BASIC)
            ->from(action([RefereesController::class, 'edit'], $this->referee))
            ->put(
                action([RefereesController::class, 'update'], $this->referee),
                RefereeRequestDataFactory::new()->create()
            )
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function a_guest_cannot_update_a_referee()
    {
        $this
            ->from(action([RefereesController::class, 'edit'], $this->referee))
            ->put(
                action([RefereesController::class, 'update'], $this->referee),
                RefereeRequestDataFactory::new()->withReferee($this->referee)->create()
            )
            ->assertRedirect(route('login'));
    }

    /**
     * @test
     */
    public function update_validates_using_a_form_request()
    {
        $this->assertActionUsesFormRequest(RefereesController::class, 'update', UpdateRequest::class);
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function an_administrator_can_delete_a_referee($administrators)
    {
        $this
            ->actAs($administrators)
            ->delete(action([RefereesController::class, 'destroy'], $this->referee))
            ->assertRedirect(action([RefereesController::class, 'index']));

        $this->assertSoftDeleted($this->referee);
    }

    /**
     * @test
     */
    public function a_basic_user_cannot_delete_a_referee()
    {
        $this
            ->actAs(Role::BASIC)
            ->delete(action([RefereesController::class, 'destroy'], $this->referee))
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function a_guest_cannot_delete_a_referee()
    {
        $this
            ->delete(action([RefereesController::class, 'destroy'], $this->referee))
            ->assertRedirect(route('login'));
    }
}
