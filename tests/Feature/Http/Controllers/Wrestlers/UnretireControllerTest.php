<?php

use App\Enums\WrestlerStatus;
use App\Exceptions\CannotBeUnretiredException;
use App\Http\Controllers\Wrestlers\UnretireController;
use App\Http\Controllers\Wrestlers\WrestlersController;
use App\Models\Wrestler;

test('invoke unretires a retired wrestler and redirects', function () {
    $wrestler = Wrestler::factory()->retired()->create();

    $this->actingAs(administrator())
        ->patch(action([UnretireController::class], $wrestler))
        ->assertRedirect(action([WrestlersController::class, 'index']));

    tap($wrestler->fresh(), function ($wrestler) {
        $this->assertNotNull($wrestler->retirements->last()->ended_at);
        $this->assertEquals(WrestlerStatus::BOOKABLE, $wrestler->status);
    });
});

test('a basic user cannot unretire a wrestler', function () {
    $wrestler = Wrestler::factory()->retired()->create();

    $this->actingAs(basicUser())
        ->patch(action([UnretireController::class], $wrestler))
        ->assertForbidden();
});

test('a guest cannot unretire a wrestler', function () {
    $wrestler = Wrestler::factory()->retired()->create();

    $this->patch(action([UnretireController::class], $wrestler))
        ->assertRedirect(route('login'));
});

test('invoke throws exception for unretiring a non unretirable wrestler', function ($factoryState) {
    $wrestler = Wrestler::factory()->{$factoryState}()->create();

    $this->actingAs(administrator())
        ->patch(action([UnretireController::class], $wrestler));
})->throws(CannotBeUnretiredException::class)->with([
    'bookable',
    'withFutureEmployment',
    'injured',
    'released',
    'suspended',
    'unemployed',
]);
