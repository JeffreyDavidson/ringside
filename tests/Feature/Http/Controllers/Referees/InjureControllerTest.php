<?php

use App\Enums\RefereeStatus;
use App\Exceptions\CannotBeInjuredException;
use App\Http\Controllers\Referees\InjureController;
use App\Http\Controllers\Referees\RefereesController;
use App\Models\Referee;

test('invoke injures a bookable referee and redirects', function () {
    $referee = Referee::factory()->bookable()->create();

    $this->actingAs(administrator())
        ->patch(action([InjureController::class], $referee))
        ->assertRedirect(action([RefereesController::class, 'index']));

    tap($referee->fresh(), function ($referee) {
        $this->assertCount(1, $referee->injuries);
        $this->assertEquals(RefereeStatus::INJURED, $referee->status);
    });
});

test('a basic user cannot injure a bookable referee', function () {
    $referee = Referee::factory()->bookable()->create();

    $this->actingAs(basicUser())
        ->patch(action([InjureController::class], $referee))
        ->assertForbidden();
});

test('a guest user cannot injure a bookable referee', function () {
    $referee = Referee::factory()->bookable()->create();

    $this->patch(action([InjureController::class], $referee))
        ->assertRedirect(route('login'));
});

test('invoke throws exception for injuring a non injurable referee', function ($factoryState) {
    $this->withoutExceptionHandling();

    $referee = Referee::factory()->{$factoryState}()->create();

    $this->actingAs(administrator())
        ->patch(action([InjureController::class], $referee));
})->throws(CannotBeInjuredException::class)->with([
    'unemployed',
    'suspended',
    'released',
    'withFutureEmployment',
    'retired',
    'injured',
]);
