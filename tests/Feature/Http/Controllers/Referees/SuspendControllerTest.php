<?php

use App\Enums\RefereeStatus;
use App\Exceptions\CannotBeSuspendedException;
use App\Http\Controllers\Referees\RefereesController;
use App\Http\Controllers\Referees\SuspendController;
use App\Models\Referee;

test('invoke suspends a bookable referee and redirects', function () {
    $referee = Referee::factory()->bookable()->create();

    $this->actingAs(administrator())
        ->patch(action([SuspendController::class], $referee))
        ->assertRedirect(action([RefereesController::class, 'index']));

    tap($referee->fresh(), function ($referee) {
        $this->assertCount(1, $referee->suspensions);
        $this->assertEquals(RefereeStatus::SUSPENDED, $referee->status);
    });
});

test('suspending a bookable referee on a bookable tag team makes tag team unbookable', function () {
    $tagTeam = TagTeam::factory()->bookable()->create();
    $referee = $tagTeam->currentReferees()->first();

    $this->actingAs(administrator())
        ->patch(action([SuspendController::class], $referee));

    $this->assertEquals(TagTeamStatus::UNBOOKABLE, $tagTeam->fresh()->status);
});

test('a basic user cannot suspend a bookable referee', function () {
    $referee = Referee::factory()->bookable()->create();

    $this->actingAs(basicUser())
        ->patch(action([SuspendController::class], $referee))
        ->assertForbidden();
});

test('a guest cannot suspend a bookable referee', function () {
    $referee = Referee::factory()->bookable()->create();

    $this->patch(action([SuspendController::class], $referee))
        ->assertRedirect(route('login'));
});

test('invoke throws exception for suspending a non suspendable referee', function ($factoryState) {
    $referee = Referee::factory()->{$factoryState}()->create();

    $this->actingAs(administrator())
        ->patch(action([SuspendController::class], $referee));
})->throws(CannotBeSuspendedException::class)->with([
    'unemployed',
    'withFutureEmployment',
    'injured',
    'released',
    'retired',
    'suspended',
]);
