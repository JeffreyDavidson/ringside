<?php

test('invoke throws exception for unretiring a non unretirable stable', function ($factoryState) {
    $this->withoutExceptionHandling();

    $stable = Stable::factory()->{$factoryState}()->create();

    $this->actingAs(administrator())
        ->patch(action([UnretireController::class], $stable));
})->throws(CannotBeUnretiredException::class)->with([
    'active',
    'withFutureActivation',
    'inactive',
    'unactivated',
]);
