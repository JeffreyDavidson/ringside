<?php

use App\Enums\TagTeamStatus;
use App\Enums\WrestlerStatus;
use App\Exceptions\CannotBeSuspendedException;
use App\Http\Controllers\Wrestlers\SuspendController;
use App\Http\Controllers\Wrestlers\WrestlersController;
use App\Models\TagTeam;
use App\Models\Wrestler;

test('invoke suspends a bookable wrestler and redirects', function () {
    $wrestler = Wrestler::factory()->bookable()->create();

    $this->actingAs(administrator())
        ->patch(action([SuspendController::class], $wrestler))
        ->assertRedirect(action([WrestlersController::class, 'index']));

    tap($wrestler->fresh(), function ($wrestler) {
        $this->assertCount(1, $wrestler->suspensions);
        $this->assertEquals(WrestlerStatus::SUSPENDED, $wrestler->status);
    });
});

test('suspending a bookable wrestler on a bookable tag team makes tag team unbookable', function () {
    $tagTeam = TagTeam::factory()->bookable()->create();
    $wrestler = $tagTeam->currentWrestlers()->first();

    $this->actingAs(administrator())
        ->patch(action([SuspendController::class], $wrestler));

    $this->assertEquals(TagTeamStatus::UNBOOKABLE, $tagTeam->fresh()->status);
});

test('a basic user cannot suspend a bookable wrestler', function () {
    $wrestler = Wrestler::factory()->bookable()->create();

    $this->actingAs(basicUser())
        ->patch(action([SuspendController::class], $wrestler))
        ->assertForbidden();
});

test('a guest cannot suspend a bookable wrestler', function () {
    $wrestler = Wrestler::factory()->bookable()->create();

    $this->patch(action([SuspendController::class], $wrestler))
        ->assertRedirect(route('login'));
});

test('invoke throws exception for suspending a non suspendable wrestler', function ($factoryState) {
    $this->withoutExceptionHandling();

    $wrestler = Wrestler::factory()->{$factoryState}()->create();

    $this->actingAs(administrator())
        ->patch(action([SuspendController::class], $wrestler));
})->throws(CannotBeSuspendedException::class)->with([
    'unemployed',
    'withFutureEmployment',
    'injured',
    'released',
    'retired',
    'suspended',
]);
