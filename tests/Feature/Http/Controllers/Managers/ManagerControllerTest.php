<?php

namespace Tests\Feature\Http\Controllers\Managers;

use App\Enums\Role;
use App\Http\Controllers\Managers\ManagersController;
use App\Http\Requests\Managers\StoreRequest;
use App\Http\Requests\Managers\UpdateRequest;
use App\Models\Manager;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\ManagerRequestDataFactory;
use Tests\TestCase;

/**
 * @group managers
 * @group feature-managers
 * @group roster
 * @group feature-roster
 */
class ManagerControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->manager = Manager::factory()->create();
        $this->factory = ManagerRequestDataFactory::new()->withManager($this->manager);
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function index_returns_a_views($administrators)
    {
        $this
            ->actAs($administrators)
            ->get(action([ManagersController::class, 'index']))
            ->assertOk()
            ->assertViewIs('managers.index')
            ->assertSeeLivewire('managers.employed-managers')
            ->assertSeeLivewire('managers.future-employed-and-unemployed-managers')
            ->assertSeeLivewire('managers.released-managers')
            ->assertSeeLivewire('managers.suspended-managers')
            ->assertSeeLivewire('managers.injured-managers')
            ->assertSeeLivewire('managers.retired-managers');
    }

    /**
     * @test
     */
    public function a_basic_user_cannot_view_managers_index_page()
    {
        $this
            ->actAs(Role::BASIC)
            ->get(action([ManagersController::class, 'index']))
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function a_guest_cannot_view_managers_index_page()
    {
        $this
            ->get(action([ManagersController::class, 'index']))
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
            ->get(action([ManagersController::class, 'create']))
            ->assertViewIs('managers.create')
            ->assertViewHas('manager', new Manager);
    }

    /**
     * @test
     */
    public function a_basic_user_cannot_view_the_form_for_creating_a_manager()
    {
        $this
            ->actAs(Role::BASIC)
            ->get(action([ManagersController::class, 'index']))
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function a_guest_cannot_view_the_form_for_creating_a_manager()
    {
        $this
            ->get(action([ManagersController::class, 'create']))
            ->assertRedirect(route('login'));
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function store_creates_a_manager_and_redirects($administrators)
    {
        $this
            ->actAs($administrators)
            ->from(action([ManagersController::class, 'create']))
            ->post(action([ManagersController::class, 'store'], ManagerRequestDataFactory::new()->create()))
            ->assertRedirect(action([ManagersController::class, 'index']));

        tap(Manager::first(), function ($manager) {
            $this->assertEquals('John', $manager->first_name);
            $this->assertEquals('Smith', $manager->last_name);
        });
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function an_employment_is_not_created_for_the_manager_if_started_at_is_filled_in_request($administrators)
    {
        $this
            ->actAs($administrators)
            ->from(action([ManagersController::class, 'create']))
            ->post(route('managers.store', ManagerRequestDataFactory::new()->create(['started_at' => null])));

        tap(Manager::first(), function ($manager) {
            $this->assertCount(0, $manager->employments);
        });
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function an_employment_is_created_for_the_manager_if_started_at_is_filled_in_request($administrators)
    {
        $startedAt = now()->toDateTimeString();

        $this
            ->actAs($administrators)
            ->from(action([ManagersController::class, 'create']))
            ->post(action([ManagersController::class, 'store'], ManagerRequestDataFactory::new()->create(['started_at' => $startedAt])));

        tap(Manager::first(), function ($manager) use ($startedAt) {
            $this->assertCount(1, $manager->employments);
            $this->assertEquals($startedAt, $manager->employments->first()->started_at->toDateTimeString());
        });
    }

    /**
     * @test
     */
    public function a_basic_user_cannot_create_a_manager()
    {
        $this
            ->actAs(Role::BASIC)
            ->from(action([ManagersController::class, 'create']))
            ->post(route('managers.store', ManagerRequestDataFactory::new()->create()))
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function a_guest_cannot_create_a_manager()
    {
        $this
            ->from(action([ManagersController::class, 'create']))
            ->post(action([ManagersController::class, 'store'], ManagerRequestDataFactory::new()->create()))
            ->assertRedirect(route('login'));
    }

    /**
     * @test
     */
    public function store_validates_using_a_form_request()
    {
        $this->assertActionUsesFormRequest(ManagersController::class, 'store', StoreRequest::class);
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function show_can_view_a_manager_profile($administrators)
    {
        $this
            ->actAs($administrators)
            ->get(action([ManagersController::class, 'show'], $this->manager))
            ->assertViewIs('managers.show')
            ->assertViewHas('manager', $this->manager);
    }

    /**
     * @test
     */
    public function a_basic_user_can_view_their_manager_profile()
    {
        $this->actAs(Role::BASIC);
        $this->manager = Manager::factory()->create(['user_id' => auth()->user()]);

        $this
            ->get(action([ManagersController::class, 'show'], $this->manager))
            ->assertOk();
    }

    /**
     * @test
     */
    public function a_basic_user_cannot_view_another_users_manager_profile()
    {
        $otherUser = User::factory()->create();
        $this->manager = Manager::factory()->create(['user_id' => $otherUser->id]);

        $this
            ->actAs(Role::BASIC)
            ->get(action([ManagersController::class, 'create'], $this->manager))
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function a_guest_cannot_view_a_manager_profile()
    {
        $this
            ->get(action([ManagersController::class, 'show'], $this->manager))
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
            ->get(action([ManagersController::class, 'edit'], $this->manager))
            ->assertViewIs('managers.edit')
            ->assertViewHas('manager', $this->manager);
    }

    /**
     * @test
     */
    public function a_basic_user_cannot_view_the_form_for_editing_a_manager()
    {
        $this
            ->actAs(Role::BASIC)
            ->get(action([ManagersController::class, 'edit'], $this->manager))
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function a_guest_cannot_view_the_form_for_editing_a_manager()
    {
        $this
            ->get(action([ManagersController::class, 'edit'], $this->manager))
            ->assertRedirect(route('login'));
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function update_a_manager_and_redirects($administrators)
    {
        $this
            ->actAs($administrators)
            ->from(action([ManagersController::class, 'edit'], $this->manager))
            ->put(action([ManagersController::class, 'update'], $this->manager), $this->factory->create())
            ->assertRedirect(action([ManagersController::class, 'edit']));

        tap($this->manager->fresh(), function ($manager) {
            $this->assertEquals('John', $manager->first_name);
            $this->assertEquals('Smith', $manager->last_name);
        });
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function update_can_employ_an_unemployed_manager_when_started_at_is_filled($administrators)
    {
        $now = now()->toDateTimeString();
        $manager = Manager::factory()->unemployed()->create();

        $this
            ->actAs($administrators)
            ->from(action([ManagersController::class, 'edit'], $this->manager))
            ->put(action([ManagersController::class, 'update'], $this->manager), $this->factory->create(['started_at' => $now]))
            ->assertRedirect(action([ManagersController::class, 'index']));

        tap($manager->fresh(), function ($manager) use ($now) {
            $this->assertCount(1, $manager->employments);
            $this->assertEquals($now, $manager->employments->first()->started_at->toDateTimeString());
        });
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function update_can_employ_a_future_employed_manager_when_started_at_is_filled($administrators)
    {
        $now = now()->toDateTimeString();
        $this->manager = Manager::factory()->withFutureEmployment()->create();

        $this
            ->actAs($administrators)
            ->from(action([ManagersController::class, 'edit'], $this->manager))
            ->put(action([ManagersController::class, 'update'], $this->manager), $this->factory->create(['started_at' => $now]))
            ->assertRedirect(action([ManagersController::class, 'index']));

        tap($this->manager->fresh(), function ($manager) use ($now) {
            $this->assertCount(1, $manager->employments);
            $this->assertEquals($now, $manager->employments()->first()->started_at->toDateTimeString());
        });
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function update_cannot_reemploy_a_released_manager($administrators)
    {
        $manager = Manager::factory()->released()->create();
        $startDate = $manager->employments->last()->started_at->toDateTimeString();

        $this->assertCount(1, $manager->employments);

        $this
            ->actAs($administrators)
            ->from(action([ManagersController::class, 'edit'], $this->manager))
            ->put(action([ManagersController::class, 'update'], $this->manager), $this->factory->create());

        tap($this->manager->fresh(), function ($manager) use ($startDate) {
            $this->assertCount(1, $manager->employments);
            $this->assertSame($startDate, $manager->employments->last()->started_at->toDateTimeString());
        });
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function updating_cannot_employ_an_available_manager_when_started_at_is_filled($administrators)
    {
        $manager = Manager::factory()->available()->create();

        $this
            ->actAs($administrators)
            ->from(action([ManagersController::class, 'edit'], $this->manager))
            ->put(
                action([ManagersController::class, 'create'], $this->manager),
                $this->factory->create(['started_at' => $manager->employments()->first()->started_at->toDateTimeString()])
            )
            ->assertRedirect(action([ManagersController::class, 'index']));

        tap($manager->fresh(), function ($manager) {
            $this->assertCount(1, $manager->employments);
        });
    }

    /**
     * @test
     */
    public function a_basic_user_cannot_update_a_manager()
    {
        $this
            ->actAs(Role::BASIC)
            ->from(action([ManagersController::class, 'edit'], $this->manager))
            ->put(action([ManagersController::class, 'update'], $this->manager), $this->factory->create())
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function a_guest_cannot_update_a_manager()
    {
        $this
            ->from(action([ManagersController::class, 'edit'], $this->manager))
            ->put(action([ManagersController::class, 'update'], $this->manager), $this->factory->create())
            ->assertRedirect(route('login'));
    }

    /**
     * @test
     */
    public function update_validates_using_a_form_request()
    {
        $this->assertActionUsesFormRequest(ManagersController::class, 'update', UpdateRequest::class);
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function delete_a_manager_and_redirects($administrators)
    {
        $this
            ->actAs($administrators)
            ->delete(action([ManagersController::class, 'destroy'], $this->manager))
            ->assertRedirect(action([ManagersController::class, 'index']));

        $this->assertSoftDeleted($this->manager);
    }

    /**
     * @test
     */
    public function a_basic_user_cannot_delete_a_manager()
    {
        $this->actAs(Role::BASIC)
            ->delete(action([ManagersController::class, 'destory'], $this->manager))
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function a_guest_cannot_delete_a_manager()
    {
        $this->delete(action([ManagersController::class, 'destroy'], $this->manager))
            ->assertRedirect(route('login'));
    }
}
