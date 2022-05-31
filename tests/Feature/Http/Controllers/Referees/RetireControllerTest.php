<?php

use App\Enums\RefereeStatus;
use App\Exceptions\CannotBeRetiredException;
use App\Http\Controllers\Referees\RefereesController;
use App\Http\Controllers\Referees\RetireController;
use App\Models\Referee;

test('invoke retires a bookable referee and redirects', function () {
    $referee = Referee::factory()->bookable()->create();

    $this->actingAs(administrator())
        ->patch(action([RetireController::class], $referee))
        ->assertRedirect(action([RefereesController::class, 'index']));

    tap($referee->fresh(), function ($referee) {
        $this->assertCount(1, $referee->retirements);
        $this->assertEquals(RefereeStatus::RETIRED, $referee->status);
    });
});

test('invoke retires an injured referee and redirects', function () {
    $referee = Referee::factory()->injured()->create();

    $this->actingAs(administrator())
        ->patch(action([RetireController::class], $referee))
        ->assertRedirect(action([RefereesController::class, 'index']));

    tap($referee->fresh(), function ($referee) {
        $this->assertCount(1, $referee->retirements);
        $this->assertEquals(RefereeStatus::RETIRED, $referee->status);
    });
});

test('invoke retires a suspended referee and redirects', function () {
    $referee = Referee::factory()->suspended()->create();

    $this->actingAs(administrator())
        ->patch(action([RetireController::class], $referee))
        ->assertRedirect(action([RefereesController::class, 'index']));

    tap($referee->fresh(), function ($referee) {
        $this->assertCount(1, $referee->retirements);
        $this->assertEquals(RefereeStatus::RETIRED, $referee->status);
    });
});

test('a basic user cannot retire a bookable referee', function () {
    $referee = Referee::factory()->bookable()->create();

    $this->actingAs(basicUser())
        ->patch(action([RetireController::class], $referee))
        ->assertForbidden();
});

test('a guest cannot suspend a bookable referee', function () {
    $referee = Referee::factory()->bookable()->create();

    $this->patch(action([RetireController::class], $referee))
        ->assertRedirect(route('login'));
});

test('invoke throws exception for retiring a non retirable referee', function ($factoryState) {
    $this->withoutExceptionHandling();

    $referee = Referee::factory()->{$factoryState}()->create();

    $this->actingAs(administrator())
        ->patch(action([RetireController::class], $referee));
})->throws(CannotBeRetiredException::class)->with([
    'retired',
    'withFutureEmployment',
    'released',
    'unemployed',
]);
