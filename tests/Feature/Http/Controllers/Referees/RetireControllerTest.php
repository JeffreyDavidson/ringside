<?php

namespace Tests\Feature\Http\Controllers\Referees;

use App\Enums\RefereeStatus;
use App\Enums\Role;
use App\Exceptions\CannotBeRetiredException;
use App\Http\Controllers\Referees\RetireController;
use App\Http\Requests\Referees\RetireRequest;
use App\Models\Referee;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group referees
 * @group feature-referees
 * @group roster
 * @group feature-roster
 */
class RetireControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @dataProvider administrators
     */
    public function invoke_retires_a_bookable_referee_and_redirects($administrators)
    {
        $now = now();
        Carbon::setTestNow($now);

        $referee = Referee::factory()->bookable()->create();

        $this->actAs($administrators)
            ->patch(route('referees.retire', $referee))
            ->assertRedirect(route('referees.index'));

        tap($referee->fresh(), function ($referee) use ($now) {
            $this->assertEquals(RefereeStatus::RETIRED, $referee->status);
            $this->assertCount(1, $referee->retirements);
            $this->assertEquals($now->toDateTimeString(), $referee->retirements->first()->started_at->toDateTimeString());
        });
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function invoke_retires_an_injured_referee_and_redirects($administrators)
    {
        $now = now();
        Carbon::setTestNow($now);

        $referee = Referee::factory()->injured()->create();

        $this->actAs($administrators)
            ->patch(route('referees.retire', $referee))
            ->assertRedirect(route('referees.index'));

        tap($referee->fresh(), function ($referee) use ($now) {
            $this->assertEquals(RefereeStatus::RETIRED, $referee->status);
            $this->assertCount(1, $referee->retirements);
            $this->assertEquals($now->toDateTimeString(), $referee->retirements->first()->started_at->toDateTimeString());
        });
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function invoke_retires_a_suspended_referee_and_redirects($administrators)
    {
        $now = now();
        Carbon::setTestNow($now);

        $referee = Referee::factory()->suspended()->create();

        $this->actAs($administrators)
            ->patch(route('referees.retire', $referee))
            ->assertRedirect(route('referees.index'));

        tap($referee->fresh(), function ($referee) use ($now) {
            $this->assertEquals(RefereeStatus::RETIRED, $referee->status);
            $this->assertCount(1, $referee->retirements);
            $this->assertEquals($now->toDateTimeString(), $referee->retirements->first()->started_at->toDateTimeString());
        });
    }

    /**
     * @test
     */
    public function invoke_validates_using_a_form_request()
    {
        $this->assertActionUsesFormRequest(RetireController::class, '__invoke', RetireRequest::class);
    }

    /**
     * @test
     */
    public function a_basic_user_cannot_retire_a_referee()
    {
        $referee = Referee::factory()->create();

        $this->actAs(Role::BASIC)
            ->patch(route('referees.retire', $referee))
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function a_guest_cannot_retire_a_referee()
    {
        $referee = Referee::factory()->create();

        $this->patch(route('referees.retire', $referee))
            ->assertRedirect(route('login'));
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function retiring_a_retired_referee_throws_an_exception($administrators)
    {
        $this->expectException(CannotBeRetiredException::class);
        $this->withoutExceptionHandling();

        $referee = Referee::factory()->retired()->create();

        $this->actAs($administrators)
            ->patch(route('referees.retire', $referee));
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function retiring_a_future_employed_referee_throws_an_exception($administrators)
    {
        $this->expectException(CannotBeRetiredException::class);
        $this->withoutExceptionHandling();

        $referee = Referee::factory()->withFutureEmployment()->create();

        $this->actAs($administrators)
            ->patch(route('referees.retire', $referee));
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function retiring_an_released_referee_throws_an_exception($administrators)
    {
        $this->expectException(CannotBeRetiredException::class);
        $this->withoutExceptionHandling();

        $referee = Referee::factory()->released()->create();

        $this->actAs($administrators)
            ->patch(route('referees.retire', $referee));
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function retiring_an_unemployed_referee_throws_an_exception($administrators)
    {
        $this->expectException(CannotBeRetiredException::class);
        $this->withoutExceptionHandling();

        $referee = Referee::factory()->retired()->create();

        $this->actAs($administrators)
            ->patch(route('referees.retire', $referee));
    }
}
