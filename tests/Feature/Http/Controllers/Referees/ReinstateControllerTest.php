<?php

namespace Tests\Feature\Http\Controllers\Referees;

use App\Enums\RefereeStatus;
use App\Enums\Role;
use App\Exceptions\CannotBeReinstatedException;
use App\Http\Controllers\Referees\RefereesController;
use App\Http\Controllers\Referees\ReinstateController;
use App\Models\Referee;
use Tests\TestCase;

/**
 * @group referees
 * @group feature-referees
 * @group roster
 * @group feature-roster
 */
class ReinstateControllerTest extends TestCase
{
    /**
     * @test
     */
    public function invoke_reinstates_a_suspended_referee_and_redirects()
    {
        $referee = Referee::factory()->suspended()->create();

        $this->assertNull($referee->currentSuspension->ended_at);

        $this
            ->actAs(Role::administrator())
            ->patch(action([ReinstateController::class], $referee))
            ->assertRedirect(action([RefereesController::class, 'index']));

        tap($referee->fresh(), function ($referee) {
            $this->assertNotNull($referee->suspensions->last()->ended_at);
            $this->assertEquals(RefereeStatus::bookable(), $referee->status);
        });
    }

    /**
     * @test
     */
    public function a_basic_user_cannot_reinstate_a_referee()
    {
        $this->actAs(Role::basic());
        $referee = Referee::factory()->create();

        $this->patch(action([ReinstateController::class], $referee))
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function a_guest_cannot_reinstate_a_referee()
    {
        $referee = Referee::factory()->create();

        $this->patch(action([ReinstateController::class], $referee))
            ->assertRedirect(route('login'));
    }

    /**
     * @test
     * @dataProvider nonreinstatableRefereeTypes
     *
     * @param mixed $factoryState
     */
    public function invoke_throws_exception_for_reinstating_a_non_reinstatable_referee($factoryState)
    {
        $this->expectException(CannotBeReinstatedException::class);
        $this->withoutExceptionHandling();

        $referee = Referee::factory()->{$factoryState}()->create();

        $this
            ->actAs(Role::administrator())
            ->patch(action([ReinstateController::class], $referee));
    }

    public function nonreinstatableRefereeTypes()
    {
        return [
            'bookable referee' => ['bookable'],
            'unemployed referee' => ['unemployed'],
            'injured referee' => ['injured'],
            'released referee' => ['released'],
            'with future employed referee' => ['withFutureEmployment'],
            'retired referee' => ['retired'],
        ];
    }
}
