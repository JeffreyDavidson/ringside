<?php

use App\Enums\RefereeStatus;
use App\Exceptions\CannotBeReleasedException;
use App\Http\Controllers\Referees\RefereesController;
use App\Http\Controllers\Referees\ReleaseController;
use App\Models\Referee;

test('invoke releases a bookable referee and redirects', function () {
    $referee = Referee::factory()->bookable()->create();

    $this->actingAs(administrator())
        ->patch(action([ReleaseController::class], $referee))
        ->assertRedirect(action([RefereesController::class, 'index']));

    tap($referee->fresh(), function ($referee) {
        $this->assertNotNull($referee->employments->last()->ended_at);
        $this->assertEquals(RefereeStatus::RELEASED, $referee->status);
    });
});

test('invoke releases an injured referee and redirects', function () {
    $referee = Referee::factory()->injured()->create();

    $this->actingAs(administrator())
        ->patch(action([ReleaseController::class], $referee))
        ->assertRedirect(action([RefereesController::class, 'index']));

    tap($referee->fresh(), function ($referee) {
        $this->assertNotNull($referee->injuries->last()->ended_at);
        $this->assertNotNull($referee->employments->last()->ended_at);
        $this->assertEquals(RefereeStatus::RELEASED, $referee->status);
    });
});

test('invoke releases an suspended referee and redirects', function () {
    $referee = Referee::factory()->suspended()->create();

    $this->actingAs(administrator())
        ->patch(action([ReleaseController::class], $referee))
        ->assertRedirect(action([RefereesController::class, 'index']));

    tap($referee->fresh(), function ($referee) {
        $this->assertNotNull($referee->suspensions->last()->ended_at);
        $this->assertNotNull($referee->employments->last()->ended_at);
        $this->assertEquals(RefereeStatus::RELEASED, $referee->status);
    });
});

test('a basic user cannot release a bookable referee', function () {
    $referee = Referee::factory()->bookable()->create();

    $this->actingAs(basicUser())
        ->patch(action([ReleaseController::class], $referee))
        ->assertForbidden();
});

test('a guest cannot release a bookable referee', function () {
    $referee = Referee::factory()->bookable()->create();

    $this->patch(action([ReleaseController::class], $referee))
        ->assertRedirect(route('login'));
});

test('invoke throws an exception for releasing a non releasable referee', function ($factoryState) {
    $this->withoutExceptionHandling();

    $referee = Referee::factory()->{$factoryState}()->create();

    $response = $this->actingAs(administrator())
        ->patch(action([ReleaseController::class], $referee));
    dd($response);
})->throws(CannotBeReleasedException::class)->with([
    'unemployed',
    'withFutureEmployment',
    'released',
    'retired',
]);
