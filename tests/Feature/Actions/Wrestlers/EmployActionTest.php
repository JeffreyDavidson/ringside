<?php

test('invoke employs an unemployed wrestler and redirects', function () {
    $wrestler = Wrestler::factory()->unemployed()->create();

    $this->actingAs(administrator())
        ->patch(action([EmployController::class], $wrestler))
        ->assertRedirect(action([WrestlersController::class, 'index']));

    expect($wrestler->fresh())
        ->employments->toHaveCount(1)
        ->status->toMatchObject(WrestlerStatus::BOOKABLE);
});

test('invoke employs a future employed wrestler and redirects', function () {
    $wrestler = Wrestler::factory()->withFutureEmployment()->create();
    $startDate = $wrestler->employments->last()->started_at;

    $this->actingAs(administrator())
        ->patch(action([EmployController::class], $wrestler))
        ->assertRedirect(action([WrestlersController::class, 'index']));

    expect($wrestler->fresh())
        ->currentEmployment->started_at->toBeLessThan($startDate)
        ->status->toMatchObject(WrestlerStatus::BOOKABLE);
});

test('invoke employs a released wrestler and redirects', function () {
    $wrestler = Wrestler::factory()->released()->create();

    $this->actingAs(administrator())
        ->patch(action([EmployController::class], $wrestler))
        ->assertRedirect(action([WrestlersController::class, 'index']));

    expect($wrestler->fresh())
        ->employments->toHaveCount(2)
        ->status->toMatchObject(WrestlerStatus::BOOKABLE);
});
