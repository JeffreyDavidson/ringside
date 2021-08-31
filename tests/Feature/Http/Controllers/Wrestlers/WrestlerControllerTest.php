<?php

namespace Tests\Feature\Http\Controllers\Wrestlers;

use App\Enums\Role;
use App\Http\Controllers\Wrestlers\WrestlersController;
use App\Http\Requests\Wrestlers\StoreRequest;
use App\Http\Requests\Wrestlers\UpdateRequest;
use App\Models\User;
use App\Models\Wrestler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\WrestlerRequestDataFactory;
use Tests\TestCase;

/**
 * @group wrestlers
 * @group feature-wrestlers
 * @group roster
 * @group feature-roster
 */
class WrestlerControllerTest extends TestCase
{
    use RefreshDatabase;

    private Wrestler $wrestler;
    private WrestlerRequestDataFactory $factory;

    public function setUp(): void
    {
        parent::setUp();

        $this->wrestler = Wrestler::factory()->create();
        $this->factory = WrestlerRequestDataFactory::new()->withWrestler($this->wrestler);
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function index_returns_a_view($administrators)
    {
        $this
            ->actAs($administrators)
            ->get(action([WrestlersController::class, 'index']))
            ->assertOk()
            ->assertViewIs('wrestlers.index')
            ->assertSeeLivewire('wrestlers.bookable-wrestlers')
            ->assertSeeLivewire('wrestlers.future-employed-and-unemployed-wrestlers')
            ->assertSeeLivewire('wrestlers.released-wrestlers')
            ->assertSeeLivewire('wrestlers.suspended-wrestlers')
            ->assertSeeLivewire('wrestlers.injured-wrestlers')
            ->assertSeeLivewire('wrestlers.retired-wrestlers');
    }

    /**
     * @test
     */
    public function a_basic_user_cannot_view_wrestlers_index_page()
    {
        $this
            ->actAs(Role::BASIC)
            ->get(action([WrestlersController::class, 'index']))
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function a_guest_cannot_view_wrestlers_index_page()
    {
        $this
            ->get(action([WrestlersController::class, 'index']))
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
            ->get(action([WrestlersController::class, 'create']))
            ->assertViewIs('wrestlers.create')
            ->assertViewHas('wrestler', new Wrestler);
    }

    /**
     * @test
     */
    public function a_basic_user_cannot_view_the_form_for_creating_a_wrestler()
    {
        $this
            ->actAs(Role::BASIC)
            ->get(action([WrestlersController::class, 'create']))
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function a_guest_cannot_view_the_form_for_creating_a_wrestler()
    {
        $this
            ->get(action([WrestlersController::class, 'create']))
            ->assertRedirect(route('login'));
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function store_creates_a_wrestler_and_redirects($administrators)
    {
        $this
            ->actAs($administrators)
            ->from(action([WrestlersController::class, 'create']))
            ->post(action([WrestlersController::class, 'store']), WrestlerRequestDataFactory::new()->create([
                'name' => 'Example Wrestler Name',
                'feet' => 6,
                'inches' => 4,
                'hometown' => 'Laraville, FL',
                'signature_move' => 'The Finisher',
            ]))
            ->assertRedirect(action([WrestlersController::class, 'index']));

        tap(Wrestler::all()->last(), function ($wrestler) {
            $this->assertEquals('Example Wrestler Name', $wrestler->name);
            $this->assertEquals(76, $wrestler->height->height);
            $this->assertEquals(240, $wrestler->weight);
            $this->assertEquals('Laraville, FL', $wrestler->hometown);
            $this->assertEquals('The Finisher', $wrestler->signature_move);
        });
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function an_employment_is_not_created_for_the_wrestler_if_started_at_is_filled_in_request($administrators)
    {
        $this
            ->actAs($administrators)
            ->from(action([WrestlersController::class, 'create']))
            ->post(
                action([WrestlersController::class, 'store']),
                WrestlerRequestDataFactory::new()->create(['started_at' => null])
            );

        tap(Wrestler::first(), function ($wrestler) {
            $this->assertCount(0, $wrestler->employments);
        });
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function an_employment_is_created_for_the_wrestler_if_started_at_is_filled_in_request($administrators)
    {
        $startedAt = now()->toDateTimeString();

        $this
            ->actAs($administrators)
            ->from(action([WrestlersController::class, 'create']))
            ->post(
                action([WrestlersController::class, 'store']),
                WrestlerRequestDataFactory::new()->create(['started_at' => $startedAt])
            );

        tap(Wrestler::all()->last(), function ($wrestler) use ($startedAt) {
            $this->assertCount(1, $wrestler->employments);
            $this->assertEquals($startedAt, $wrestler->employments->first()->started_at->toDateTimeString());
        });
    }

    /**
     * @test
     */
    public function a_basic_user_cannot_create_a_wrestler()
    {
        $this
            ->actAs(Role::BASIC)
            ->from(action([WrestlersController::class, 'create']))
            ->post(action([WrestlersController::class, 'store']), WrestlerRequestDataFactory::new()->create())
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function a_guest_cannot_create_a_wrestler()
    {
        $this
            ->from(action([WrestlersController::class, 'create']))
            ->post(action([WrestlersController::class, 'store']), WrestlerRequestDataFactory::new()->create())
            ->assertRedirect(route('login'));
    }

    /**
     * @test
     */
    public function store_validates_using_a_form_request()
    {
        $this->assertActionUsesFormRequest(WrestlersController::class, 'store', StoreRequest::class);
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function show_returns_a_view($administrators)
    {
        $this
            ->actAs($administrators)
            ->get(action([WrestlersController::class, 'show'], $this->wrestler))
            ->assertViewIs('wrestlers.show')
            ->assertViewHas('wrestler', $this->wrestler);
    }

    /**
     * @test
     */
    public function a_basic_user_can_view_their_wrestler_profile()
    {
        $this->actAs(Role::BASIC);
        $this->wrestler = Wrestler::factory()->create(['user_id' => auth()->user()]);

        $this
            ->get(action([WrestlersController::class, 'show'], $this->wrestler))
            ->assertOk();
    }

    /**
     * @test
     */
    public function a_basic_user_cannot_view_another_users_wrestler_profile()
    {
        $otherUser = User::factory()->create();
        $this->wrestler = Wrestler::factory()->create(['user_id' => $otherUser->id]);

        $this
            ->actAs(Role::BASIC)
            ->get(action([WrestlersController::class, 'show'], $this->wrestler))
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function a_guest_cannot_view_a_wrestler_profile()
    {
        $this
            ->get(action([WrestlersController::class, 'show'], $this->wrestler))
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
            ->get(action([WrestlersController::class, 'edit'], $this->wrestler))
            ->assertViewIs('wrestlers.edit')
            ->assertViewHas('wrestler', $this->wrestler);
    }

    /**
     * @test
     */
    public function a_basic_user_cannot_view_the_form_for_editing_a_wrestler()
    {
        $wrestler = Wrestler::factory()->create();

        $this
            ->actAs(Role::BASIC)
            ->get(route('wrestlers.edit', $wrestler))
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function a_guest_cannot_view_the_form_for_editing_a_wrestler()
    {
        $wrestler = Wrestler::factory()->create();

        $this
            ->get(route('wrestlers.edit', $wrestler))
            ->assertRedirect(route('login'));
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function updates_a_wrestler_and_redirects($administrators)
    {
        $this
            ->actAs($administrators)
            ->from(action([WrestlersController::class, 'edit'], $this->wrestler))
            ->patch(
                action([WrestlersController::class, 'update'], $this->wrestler),
                $this->factory->create()
            )
            ->assertRedirect(action([WrestlersController::class, 'index']));

        tap($this->wrestler->fresh(), function ($wrestler) {
            $this->assertEquals('Example Wrestler Name', $wrestler->name);
            $this->assertEquals(78, $wrestler->height->inInches());
            $this->assertEquals(240, $wrestler->weight);
            $this->assertEquals('Laraville, FL', $wrestler->hometown);
            $this->assertEquals('The Signature Move', $wrestler->signature_move);
        });
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function update_can_employ_an_unemployed_wrestler_when_started_at_is_filled($administrators)
    {
        $now = now();

        $this->wrestler = Wrestler::factory()->unemployed()->create();

        $this
            ->actAs($administrators)
            ->from(action([WrestlersController::class, 'edit'], $this->wrestler))
            ->patch(
                action([WrestlersController::class, 'update'], $this->wrestler),
                $this->factory->create(['started_at' => $now->toDateTimeString()])
            )
            ->assertRedirect(action([WrestlersController::class, 'index']));

        tap($this->wrestler->fresh(), function ($wrestler) use ($now) {
            $this->assertCount(1, $wrestler->employments);
            $this->assertEquals(
                $now->toDateTimeString('minute'),
                $wrestler->employments->first()->started_at->toDateTimeString('minute')
            );
        });
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function update_can_employ_a_future_employed_wrestler_when_started_at_is_filled($administrators)
    {
        $now = now();
        $this->wrestler = Wrestler::factory()->withFutureEmployment()->create();

        $this
            ->actAs($administrators)
            ->from(action([WrestlersController::class, 'edit'], $this->wrestler))
            ->patch(
                action([WrestlersController::class, 'update'], $this->wrestler),
                $this->factory->create(['started_at' => $now->toDateTimeString()])
            )
            ->assertRedirect(action([WrestlersController::class, 'index']));

        tap($this->wrestler->fresh(), function ($wrestler) use ($now) {
            $this->assertCount(1, $wrestler->employments);
            $this->assertEquals(
                $now->toDateTimeString(),
                $wrestler->employments()->first()->started_at->toDateTimeString()
            );
        });
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function updating_cannot_employ_a_bookable_wrestler_when_started_at_is_filled($administrators)
    {
        $this->wrestler = Wrestler::factory()->bookable()->create();

        $this
            ->actAs($administrators)
            ->from(action([WrestlersController::class, 'edit'], $this->wrestler))
            ->patch(
                action([WrestlersController::class, 'update'], $this->wrestler),
                $this->factory->create(
                    ['started_at' => $this->wrestler->employments()->first()->started_at->toDateTimeString()]
                )
            )
            ->assertRedirect(action([WrestlersController::class, 'index']));

        tap($this->wrestler->fresh(), function ($wrestler) {
            $this->assertCount(1, $wrestler->employments);
        });
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function updating_cannot_reemploy_a_released_wrestler($administrators)
    {
        $this->wrestler = Wrestler::factory()->released()->create();
        $startDate = $this->wrestler->employments->last()->started_at->toDateTimeString();

        $this
            ->actAs($administrators)
            ->from(action([WrestlersController::class, 'edit'], $this->wrestler))
            ->patch(action([WrestlersController::class, 'update'], $this->wrestler), $this->factory->create())
            ->assertRedirect(action([WrestlersController::class, 'index']));

        tap($this->wrestler->fresh(), function ($wrestler) use ($startDate) {
            $this->assertCount(1, $wrestler->employments);
            $this->assertSame($startDate, $wrestler->employments->last()->started_at->toDateTimeString());
        });
    }

    /**
     * @test
     */
    public function a_basic_user_cannot_update_a_wrestler()
    {
        $this->actAs(Role::BASIC)
            ->from(action([WrestlersController::class, 'edit'], $this->wrestler))
            ->patch(action([WrestlersController::class, 'update'], $this->wrestler), $this->factory->create())
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function a_guest_cannot_update_a_wrestler()
    {
        $this
            ->from(action([WrestlersController::class, 'edit'], $this->wrestler))
            ->patch(action([WrestlersController::class, 'update'], $this->wrestler), $this->factory->create())
            ->assertRedirect(route('login'));
    }

    /**
     * @test
     */
    public function update_validates_using_a_form_request()
    {
        $this->assertActionUsesFormRequest(WrestlersController::class, 'update', UpdateRequest::class);
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function deletes_a_wrestler_and_redirects($administrators)
    {
        $this
            ->actAs($administrators)
            ->delete(action([WrestlersController::class, 'destroy'], $this->wrestler))
            ->assertRedirect(action([WrestlersController::class, 'index']));

        $this->assertSoftDeleted($this->wrestler);
    }

    /**
     * @test
     */
    public function a_basic_user_cannot_delete_a_wrestler()
    {
        $this
            ->actAs(Role::BASIC)
            ->delete(action([WrestlersController::class, 'destroy'], $this->wrestler))
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function a_guest_cannot_delete_a_wrestler()
    {
        $this
            ->delete(action([WrestlersController::class, 'destroy'], $this->wrestler))
            ->assertRedirect(route('login'));
    }
}
