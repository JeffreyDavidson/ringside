<?php

namespace Tests\Feature\Http\Controllers\Titles;

use App\Enums\Role;
use App\Http\Controllers\Titles\TitlesController;
use App\Http\Requests\Titles\StoreRequest;
use App\Http\Requests\Titles\UpdateRequest;
use App\Models\Title;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\TitleRequestDataFactory;
use Tests\TestCase;

/**
 * @group titles
 * @group feature-titles
 */
class TitlesControllerTest extends TestCase
{
    use RefreshDatabase;

    private Title $title;
    private TitleRequestDataFactory $factory;

    public function setUp(): void
    {
        parent::setUp();

        $this->title = Title::factory()->create();
        $this->factory = TitleRequestDataFactory::new()->withTitle($this->title);
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function index_returns_a_view($administrators)
    {
        $this
            ->actAs($administrators)
            ->get(action([TitlesController::class, 'index']))
            ->assertOk()
            ->assertViewIs('titles.index')
            ->assertSeeLivewire('titles.active-titles')
            ->assertSeeLivewire('titles.future-activation-and-unactivated-titles')
            ->assertSeeLivewire('titles.inactive-titles')
            ->assertSeeLivewire('titles.retired-titles');
    }

    /**
     * @test
     */
    public function a_basic_user_cannot_view_titles_index_page()
    {
        $this
            ->actAs(Role::BASIC)
            ->get(action([TitlesController::class, 'index']))
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function a_guest_cannot_view_titles_index_page()
    {
        $this
            ->get(action([TitlesController::class, 'index']))
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
            ->get(action([TitlesController::class, 'create']))
            ->assertViewIs('titles.create')
            ->assertViewHas('title', new Title);
    }

    /**
     * @test
     */
    public function a_basic_user_cannot_view_the_form_for_creating_a_title()
    {
        $this
            ->actAs(Role::BASIC)
            ->get(action([TitlesController::class, 'create']))
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function guests_cannot_view_the_form_for_creating_a_title()
    {
        $this
            ->get(action([TitlesController::class, 'create']))
            ->assertRedirect(route('login'));
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function store_creates_a_title_and_redirects($administrators)
    {
        $this
            ->actAs($administrators)
            ->from(action([TitlesController::class, 'create']))
            ->post(action([TitlesController::class, 'store']), TitleRequestDataFactory::new()->create())
            ->assertRedirect(action([TitlesController::class, 'index']));

        tap(Title::all()->last(), function ($title) {
            $this->assertEquals('Example Title', $title->name);
        });
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function an_activation_is_not_created_for_the_title_if_activated_at_is_filled_in_request($administrators)
    {
        $this
            ->actAs($administrators)
            ->from(action([TitlesController::class, 'create']))
            ->post(
                action([TitlesController::class, 'store']),
                TitleRequestDataFactory::new()->create(['activated_at' => null])
            );

        tap(Title::first(), function ($title) {
            $this->assertFalse($title->hasActivations());
        });
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function an_activation_is_created_for_the_title_if_activated_at_is_filled_in_request($administrators)
    {
        $activatedAt = now()->toDateTimeString();

        $this
            ->actAs($administrators)
            ->from(action([TitlesController::class, 'create']))
            ->post(
                action([TitlesController::class, 'store']),
                TitleRequestDataFactory::new()->create(['activated_at' => $activatedAt])
            );

        tap(Title::all()->last(), function ($title) use ($activatedAt) {
            $this->assertTrue($title->hasActivations());
            $this->assertEquals($activatedAt, $title->activations->first()->started_at->toDateTimeString());
        });
    }

    /**
     * @test
     */
    public function a_basic_user_cannot_create_a_title()
    {
        $this
            ->actAs(Role::BASIC)
            ->from(action([TitlesController::class, 'create']))
            ->post(action([TitlesController::class, 'store']), TitleRequestDataFactory::new()->create())
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function guests_cannot_create_a_title()
    {
        $this
            ->from(action([TitlesController::class, 'create']))
            ->post(action([TitlesController::class, 'store']), TitleRequestDataFactory::new()->create())
            ->assertRedirect(route('login'));
    }

    /**
     * @test
     */
    public function store_validates_using_a_form_request()
    {
        $this->assertActionUsesFormRequest(TitlesController::class, 'store', StoreRequest::class);
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function edit_returns_a_view($administrators)
    {
        $this
            ->actAs($administrators)
            ->get(action([TitlesController::class, 'edit'], $this->title))
            ->assertViewIs('titles.edit')
            ->assertViewHas('title', $this->title);
    }

    /**
     * @test
     */
    public function a_basic_user_cannot_view_the_form_for_editing_a_title()
    {
        $this->actAs(Role::BASIC)
            ->get(action([TitlesController::class, 'edit'], $this->title))
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function a_guest_cannot_view_the_form_for_editing_a_title()
    {
        $this
            ->get(action([TitlesController::class, 'edit'], $this->title))
            ->assertRedirect(route('login'));
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function update_a_title($administrators)
    {
        $this
            ->actAs($administrators)
            ->from(action([TitlesController::class, 'edit'], $this->title))
            ->put(action([TitlesController::class, 'update'], $this->title), $this->factory->create())
            ->assertRedirect(action([TitlesController::class, 'index']));

        tap($this->title->fresh(), function ($title) {
            $this->assertEquals('Example Title', $title->name);
        });
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function update_can_activate_an_unactivated_title_when_activated_at_is_filled($administrators)
    {
        $this->title = Title::factory()->unactivated()->create();

        $this
            ->actAs($administrators)
            ->from(action([TitlesController::class, 'edit'], $this->title))
            ->put(
                action([TitlesController::class, 'update'], $this->title),
                $this->factory->create(['activated_at' => now()->toDateTimeString()])
            )
            ->assertRedirect(action([TitlesController::class, 'index']));

        tap($this->title->fresh(), function ($title) {
            $this->assertCount(1, $title->activations);
        });
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function update_can_activate_a_future_activated_title_when_activated_at_is_filled($administrators)
    {
        $this->title = Title::factory()->withFutureActivation()->create();
        $startDate = $this->title->activations->last()->started_at->toDateTimeString();

        $this
            ->actAs($administrators)
            ->from(action([TitlesController::class, 'edit'], $this->title))
            ->put(action([TitlesController::class, 'update'], $this->title), $this->factory->create())
            ->assertRedirect(action([TitlesController::class, 'index']));

        tap($this->title->fresh(), function ($title) use ($startDate) {
            $this->assertCount(1, $title->activations);
            $this->assertEquals($startDate, $title->activations()->first()->started_at->toDateTimeString());
        });
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function update_cannot_activate_an_inactive_title($administrators)
    {
        $this->title = Title::factory()->inactive()->create();
        $startDate = $this->title->activations->last()->started_at->toDateTimeString();

        $this->actAs($administrators)
            ->from(action([TitlesController::class, 'edit'], $this->title))
            ->put(action([TitlesController::class, 'update'], $this->title), $this->factory->create())
            ->assertRedirect(action([TitlesController::class, 'index']));

        tap($this->title->fresh(), function ($title) use ($startDate) {
            $this->assertCount(1, $title->activations);
            $this->assertSame($startDate, $title->activations->last()->started_at->toDateTimeString());
        });
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function updating_cannot_activate_an_active_title($administrators)
    {
        $this->title = Title::factory()->active()->create();
        $startDate = $this->title->activations->last()->started_at->toDateTimeString();

        $this
            ->actAs($administrators)
            ->from(action([TitlesController::class, 'edit'], $this->title))
            ->put(action([TitlesController::class, 'update'], $this->title), $this->factory->create())
            ->assertRedirect(action([TitlesController::class, 'index']));

        tap($this->title->fresh(), function ($title) use ($startDate) {
            $this->assertCount(1, $title->activations);
            $this->assertSame($startDate, $title->activations->last()->started_at->toDateTimeString());
        });
    }

    /**
     * @test
     */
    public function a_basic_user_cannot_update_a_title()
    {
        $this
            ->actAs(Role::BASIC)
            ->from(action([TitlesController::class, 'edit'], $this->title))
            ->put(action([TitlesController::class, 'update'], $this->title), $this->factory->create())
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function a_guest_cannot_update_a_title()
    {
        $this
            ->from(action([TitlesController::class, 'edit'], $this->title))
            ->put(action([TitlesController::class, 'update'], $this->title), $this->factory->create())
            ->assertRedirect(route('login'));
    }

    /**
     * @test
     */
    public function update_validates_using_a_form_request()
    {
        $this->assertActionUsesFormRequest(TitlesController::class, 'update', UpdateRequest::class);
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function a_title_can_be_viewed($administrators)
    {
        $this
            ->actAs($administrators)
            ->get(action([TitlesController::class, 'show'], $this->title))
            ->assertViewIs('titles.show')
            ->assertViewHas('title', $this->title);
    }

    /**
     * @test
     */
    public function a_basic_user_can_view_a_title()
    {
        $this
            ->actAs(Role::BASIC)
            ->get(action([TitlesController::class, 'show'], $this->title))
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function a_guest_cannot_view_a_title()
    {
        $this
            ->get(action([TitlesController::class, 'show'], $this->title))
            ->assertRedirect(route('login'));
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function delete_a_title($administrators)
    {
        $this
            ->actAs($administrators)
            ->delete(action([TitlesController::class, 'destroy'], $this->title))
            ->assertRedirect(action([TitlesController::class, 'index']));

        $this->assertSoftDeleted($this->title);
    }

    /**
     * @test
     */
    public function a_basic_user_cannot_delete_a_title()
    {
        $this
            ->actAs(Role::BASIC)
            ->delete(action([TitlesController::class, 'destroy'], $this->title))
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function a_guest_cannot_delete_a_title()
    {
        $this
            ->delete(action([TitlesController::class, 'destroy'], $this->title))
            ->assertRedirect(route('login'));
    }
}
