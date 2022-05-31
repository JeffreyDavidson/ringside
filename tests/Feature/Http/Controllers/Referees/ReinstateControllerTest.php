<?php

use App\Enums\RefereeStatus;
use App\Exceptions\CannotBeReinstatedException;
use App\Http\Controllers\Referees\RefereesController;
use App\Http\Controllers\Referees\ReinstateController;
use App\Models\Referee;

test('invoke reinstates a suspended referee and redirects', function () {
    $referee = Referee::factory()->suspended()->create();

    $this->actingAs(administrator())
        ->patch(action([ReinstateController::class], $referee))
        ->assertRedirect(action([RefereesController::class, 'index']));

    tap($referee->fresh(), function ($referee) {
        $this->assertNotNull($referee->suspensions->last()->ended_at);
        $this->assertEquals(RefereeStatus::BOOKABLE, $referee->status);
    });
});

test('a basic user cannot reinstate a suspended referee', function () {
    $referee = Referee::factory()->suspended()->create();

    $this->actingAs(basicUser())
        ->patch(action([ReinstateController::class], $referee))
        ->assertForbidden();
});

test('a guest cannot reinstate a suspended referee', function () {
    $referee = Referee::factory()->suspended()->create();

    $this->patch(action([ReinstateController::class], $referee))
        ->assertRedirect(route('login'));
});

test('invoke throws exception for reinstating a non reinstatable referee', function ($factoryState) {
    $this->withoutExceptionHandling();

    $referee = Referee::factory()->{$factoryState}()->create();

    $this->actingAs(administrator())
        ->patch(action([ReinstateController::class], $referee));
})->throws(CannotBeReinstatedException::class)->with([
    'bookable',
    'unemployed',
    'injured',
    'released',
    'withFutureEmployment',
    'retired',
]);
