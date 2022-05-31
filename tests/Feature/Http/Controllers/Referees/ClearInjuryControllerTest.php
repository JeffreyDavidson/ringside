<?php

use App\Enums\RefereeStatus;
use App\Exceptions\CannotBeClearedFromInjuryException;
use App\Http\Controllers\Referees\ClearInjuryController;
use App\Http\Controllers\Referees\RefereesController;
use App\Models\Referee;

test('invoke marks an injured referee as being cleared from injury and redirects', function () {
    $referee = Referee::factory()->injured()->create();

    $this->actingAs(administrator())
        ->patch(action([ClearInjuryController::class], $referee))
        ->assertRedirect(action([RefereesController::class, 'index']));

    tap($referee->fresh(), function ($referee) {
        $this->assertNotNull($referee->injuries->last()->ended_at);
        $this->assertEquals(RefereeStatus::BOOKABLE, $referee->status);
    });
});

test('a basic user cannot mark an injured referee as cleared', function () {
    $referee = Referee::factory()->injured()->create();

    $this->actingAs(basicUser())
        ->patch(action([ClearInjuryController::class], $referee))
        ->assertForbidden();
});

test('a guest cannot mark an injured referee as cleared', function () {
    $referee = Referee::factory()->injured()->create();

    $this->patch(action([ClearInjuryController::class], $referee))
        ->assertRedirect(route('login'));
});

test('invoke throws exception for injuring a non injurable referee', function ($factoryState) {
    $this->withoutExceptionHandling();

    $referee = Referee::factory()->{$factoryState}()->create();

    $this->actingAs(administrator())
        ->patch(action([ClearInjuryController::class], $referee));
})->throws(CannotBeClearedFromInjuryException::class)->with([
    'unemployed',
    'released',
    'withFutureEmployment',
    'bookable',
    'retired',
    'suspended',
]);
