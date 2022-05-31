<?php

use App\Enums\RefereeStatus;
use App\Exceptions\CannotBeUnretiredException;
use App\Http\Controllers\Referees\RefereesController;
use App\Http\Controllers\Referees\UnretireController;
use App\Models\Referee;

test('invoke unretires a retired referee and redirects', function () {
    $referee = Referee::factory()->retired()->create();

    $this->actingAs(administrator())
        ->patch(action([UnretireController::class], $referee))
        ->assertRedirect(action([RefereesController::class, 'index']));

    tap($referee->fresh(), function ($referee) {
        $this->assertNotNull($referee->retirements->last()->ended_at);
        $this->assertEquals(RefereeStatus::BOOKABLE, $referee->status);
    });
});

test('a basic user cannot unretire a referee', function () {
    $referee = Referee::factory()->retired()->create();

    $this->actingAs(basicUser())
        ->patch(action([UnretireController::class], $referee))
        ->assertForbidden();
});

test('a guest cannot unretire a referee', function () {
    $referee = Referee::factory()->retired()->create();

    $this->patch(action([UnretireController::class], $referee))
        ->assertRedirect(route('login'));
});

test('invoke throws exception for unretiring a non unretirable referee', function ($factoryState) {
    $this->withoutExceptionHandling();

    $referee = Referee::factory()->{$factoryState}()->create();

    $this->actingAs(administrator())
        ->patch(action([UnretireController::class], $referee));
})->throws(CannotBeUnretiredException::class)->with([
    'bookable',
    'withFutureEmployment',
    'injured',
    'released',
    'suspended',
    'unemployed',
]);
