<?php

use App\Enums\TagTeamStatus;
use App\Enums\WrestlerStatus;
use App\Exceptions\CannotBeInjuredException;
use App\Http\Controllers\Wrestlers\InjureController;
use App\Http\Controllers\Wrestlers\WrestlersController;
use App\Models\TagTeam;
use App\Models\Wrestler;

test('invoke injures a bookable wrestler and redirects', function () {
    $wrestler = Wrestler::factory()->bookable()->create();

    $this->actingAs(administrator())
        ->patch(action([InjureController::class], $wrestler))
        ->assertRedirect(action([WrestlersController::class, 'index']));

    tap($wrestler->fresh(), function ($wrestler) {
        $this->assertCount(1, $wrestler->injuries);
        $this->assertEquals(WrestlerStatus::INJURED, $wrestler->status);
    });
});

test('injuring a bookable wrestler on a bookable tag team makes tag team unbookable', function () {
    $tagTeam = TagTeam::factory()->bookable()->create();
    $wrestler = $tagTeam->currentWrestlers()->first();

    $this->actingAs(administrator())
        ->patch(action([InjureController::class], $wrestler))
        ->assertRedirect(action([WrestlersController::class, 'index']));

    tap($tagTeam->fresh(), function ($tagTeam) {
        $this->assertEquals(TagTeamStatus::UNBOOKABLE, $tagTeam->status);
    });
});

test('a basic user cannot injure a bookable wrestler', function () {
    $wrestler = Wrestler::factory()->bookable()->create();

    $this->actingAs(basicUser())
        ->patch(action([InjureController::class], $wrestler))
        ->assertForbidden();
});

test('a guest user cannot injure a bookable wrestler', function () {
    $wrestler = Wrestler::factory()->bookable()->create();

    $this->patch(action([InjureController::class], $wrestler))
        ->assertRedirect(route('login'));
});

test('invoke throws exception for injuring a non injurable wrestler', function ($factoryState) {
    $wrestler = Wrestler::factory()->{$factoryState}()->create();

    $this->actingAs(administrator())
        ->patch(action([InjureController::class], $wrestler));
})->throws(CannotBeInjuredException::class)->with([
    'unemployed',
    'suspended',
    'released',
    'withFutureEmployment',
    'retired',
    'injured',
]);
