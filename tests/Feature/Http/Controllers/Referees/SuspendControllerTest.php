<?php

namespace Tests\Feature\Http\Controllers\Referees;

use App\Enums\RefereeStatus;
use App\Enums\Role;
use App\Exceptions\CannotBeSuspendedException;
use App\Http\Controllers\Referees\SuspendController;
use App\Http\Requests\Referees\SuspendRequest;
use App\Models\Referee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group referees
 * @group feature-referees
 * @group roster
 * @group feature-rosters
 */
class SuspendControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @dataProvider administrators
     */
    public function invoke_suspends_a_bookable_referee_and_redirects($administrators)
    {
        $referee = Referee::factory()->bookable()->create();

        $this->actAs($administrators)
            ->patch(route('referees.suspend', $referee))
            ->assertRedirect(route('referees.index'));

        tap($referee->fresh(), function ($referee) {
            $this->assertCount(1, $referee->suspensions);
            $this->assertEquals(RefereeStatus::SUSPENDED, $referee->status);
        });
    }

    /**
     * @test
     */
    public function invoke_validates_using_a_form_request()
    {
        $this->assertActionUsesFormRequest(SuspendController::class, '__invoke', SuspendRequest::class);
    }

    /**
     * @test
     */
    public function a_basic_user_cannot_suspend_a_referee()
    {
        $referee = Referee::factory()->create();

        $this->actAs(Role::BASIC)
            ->patch(route('referees.suspend', $referee))
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function a_guest_cannot_suspend_a_referee()
    {
        $referee = Referee::factory()->create();

        $this->patch(route('referees.suspend', $referee))
            ->assertRedirect(route('login'));
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function invoke_throws_exception_for_suspending_an_unemployed_referee($administrators)
    {
        $this->expectException(CannotBeSuspendedException::class);
        $this->withoutExceptionHandling();

        $referee = Referee::factory()->unemployed()->create();

        $this->actAs($administrators)
            ->patch(route('referees.suspend', $referee));
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function invoke_throws_exception_for_suspending_a_future_employed_referee($administrators)
    {
        $this->expectException(CannotBeSuspendedException::class);
        $this->withoutExceptionHandling();

        $referee = Referee::factory()->withFutureEmployment()->create();

        $this->actAs($administrators)
            ->patch(route('referees.suspend', $referee));
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function invoke_throws_exception_for_suspending_an_injured_referee($administrators)
    {
        $this->expectException(CannotBeSuspendedException::class);
        $this->withoutExceptionHandling();

        $referee = Referee::factory()->injured()->create();

        $this->actAs($administrators)
            ->patch(route('referees.suspend', $referee));
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function invoke_throws_exception_for_suspending_a_released_referee($administrators)
    {
        $this->expectException(CannotBeSuspendedException::class);
        $this->withoutExceptionHandling();

        $referee = Referee::factory()->released()->create();

        $this->actAs($administrators)
            ->patch(route('referees.suspend', $referee));
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function invoke_throws_exception_for_suspending_a_retired_referee($administrators)
    {
        $this->expectException(CannotBeSuspendedException::class);
        $this->withoutExceptionHandling();

        $referee = Referee::factory()->retired()->create();

        $this->actAs($administrators)
            ->patch(route('referees.suspend', $referee));
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function invoke_throws_exception_for_suspending_a_suspended_referee($administrators)
    {
        $this->expectException(CannotBeSuspendedException::class);
        $this->withoutExceptionHandling();

        $referee = Referee::factory()->suspended()->create();

        $this->actAs($administrators)
            ->patch(route('referees.suspend', $referee));
    }
}
