<?php

use App\Enums\TagTeamStatus;
use App\Enums\WrestlerStatus;
use App\Exceptions\CannotBeRetiredException;
use App\Http\Controllers\Wrestlers\RetireController;
use App\Http\Controllers\Wrestlers\WrestlersController;
use App\Models\TagTeam;
use App\Models\Wrestler;

test('invoke retires a bookable wrestler and redirects', function () {
    $wrestler = Wrestler::factory()->bookable()->create();

    $this->actingAs(administrator())
        ->patch(action([RetireController::class], $wrestler))
        ->assertRedirect(action([WrestlersController::class, 'index']));

    tap($wrestler->fresh(), function ($wrestler) {
        $this->assertCount(1, $wrestler->retirements);
        $this->assertEquals(WrestlerStatus::RETIRED, $wrestler->status);
    });
});

test('invoke retires an injured wrestler and redirects', function () {
    $wrestler = Wrestler::factory()->injured()->create();

    $this->actingAs(administrator())
        ->patch(action([RetireController::class], $wrestler))
        ->assertRedirect(action([WrestlersController::class, 'index']));

    tap($wrestler->fresh(), function ($wrestler) {
        $this->assertCount(1, $wrestler->retirements);
        $this->assertEquals(WrestlerStatus::RETIRED, $wrestler->status);
    });
});

test('invoke retires a suspended wrestler and redirects', function () {
    $wrestler = Wrestler::factory()->suspended()->create();

    $this->actingAs(administrator())
        ->patch(action([RetireController::class], $wrestler))
        ->assertRedirect(action([WrestlersController::class, 'index']));

    tap($wrestler->fresh(), function ($wrestler) {
        $this->assertCount(1, $wrestler->retirements);
        $this->assertEquals(WrestlerStatus::RETIRED, $wrestler->status);
    });
});

test('retiring a bookable wrestler on a bookable tag team makes tag team unbookable', function () {
    $tagTeam = TagTeam::factory()->bookable()->create();
    $wrestler = $tagTeam->wrestlers()->first();

    $this->actingAs(administrator())
        ->patch(action([RetireController::class], $wrestler))
        ->assertRedirect(action([WrestlersController::class, 'index']));

    tap($tagTeam->fresh(), function ($tagTeam) {
        $this->assertEquals(TagTeamStatus::UNBOOKABLE, $tagTeam->status);
    });
});

test('a basic user cannot retire a bookable wrestler', function () {
    $wrestler = Wrestler::factory()->bookable()->create();

    $this->actingAs(basicUser())
        ->patch(action([RetireController::class], $wrestler))
        ->assertForbidden();
});

test('a guest cannot suspend a bookable wrestler', function () {
    $wrestler = Wrestler::factory()->bookable()->create();

    $this->patch(action([RetireController::class], $wrestler))
        ->assertRedirect(route('login'));
});

test('invoke throws exception for retiring a non retirable wrestler', function ($factoryState) {
    $wrestler = Wrestler::factory()->{$factoryState}()->create();

    $this->actingAs(administrator())
        ->patch(action([RetireController::class], $wrestler));
})->throws(CannotBeRetiredException::class)->with([
    'retired',
    'withFutureEmployment',
    'released',
    'unemployed',
]);
