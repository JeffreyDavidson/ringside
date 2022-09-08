<?php

use App\Actions\Wrestlers\EmployAction;
use App\Http\Controllers\Wrestlers\EmployController;
use App\Http\Controllers\Wrestlers\WrestlersController;
use App\Models\Wrestler;

beforeEach(function () {
    $this->wrestler = Wrestler::factory()->unemployed()->create();
});

test('invoke calls employ action and redirects', function () {
    $this->actingAs(administrator())
        ->patch(action([EmployController::class], $this->wrestler))
        ->assertRedirect(action([WrestlersController::class, 'index']));

    EmployAction::shouldRun()->with($this->wrestler);
});

test('a basic user cannot employ a wrestler', function () {
    $this->actingAs(basicUser())
        ->patch(action([EmployController::class], $this->wrestler))
        ->assertForbidden();
});

test('a guest user cannot employ a wrestler', function () {
    $this->patch(action([EmployController::class], $this->wrestler))
        ->assertRedirect(route('login'));
});
