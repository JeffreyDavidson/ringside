<?php

use App\Enums\WrestlerStatus;
use App\Exceptions\CannotBeEmployedException;
use App\Http\Controllers\Wrestlers\EmployController;
use App\Http\Controllers\Wrestlers\WrestlersController;
use App\Models\Wrestler;

test('invoke employs an unemployed wrestler and redirects', function () {
    $wrestler = Wrestler::factory()->unemployed()->create();

    $this->actingAs(administrator())
        ->patch(action([EmployController::class], $wrestler))
        ->assertRedirect(action([WrestlersController::class, 'index']));

    tap($wrestler->fresh(), function ($wrestler) {
        $this->assertCount(1, $wrestler->employments);
        $this->assertEquals(WrestlerStatus::BOOKABLE, $wrestler->status);
    });
});

test('invoke employs a future employed wrestler and redirects', function () {
    $wrestler = Wrestler::factory()->withFutureEmployment()->create();
    $startedAt = $wrestler->employments->last()->started_at;

    $this->actingAs(administrator())
        ->patch(action([EmployController::class], $wrestler))
        ->assertRedirect(action([WrestlersController::class, 'index']));

    tap($wrestler->fresh(), function ($wrestler) use ($startedAt) {
        $this->assertTrue($wrestler->currentEmployment->started_at->lt($startedAt));
        $this->assertEquals(WrestlerStatus::BOOKABLE, $wrestler->status);
    });
});

test('invoke employs a released wrestler and redirects', function () {
    $wrestler = Wrestler::factory()->released()->create();

    $this->actingAs(administrator())
        ->patch(action([EmployController::class], $wrestler))
        ->assertRedirect(action([WrestlersController::class, 'index']));

    tap($wrestler->fresh(), function ($wrestler) {
        $this->assertCount(2, $wrestler->employments);
        $this->assertEquals(WrestlerStatus::BOOKABLE, $wrestler->status);
    });
});

test('a basic user cannot employ a wrestler', function () {
    $wrestler = Wrestler::factory()->create();

    $this->actingAs(basicUser())
        ->patch(action([EmployController::class], $wrestler))
        ->assertForbidden();
});

test('a guest user cannot injure a wrestler', function () {
    $wrestler = Wrestler::factory()->create();

    $this->patch(action([EmployController::class], $wrestler))
        ->assertRedirect(route('login'));
});

test('invoke throws exception for injuring a non injurable wrestler', function ($factoryState) {
    $wrestler = Wrestler::factory()->{$factoryState}()->create();

    $this->actingAs(administrator())
        ->patch(action([EmployController::class], $wrestler));
})->throws(CannotBeEmployedException::class)->with([
    'suspended',
    'injured',
    'bookable',
    'retired',
]);
