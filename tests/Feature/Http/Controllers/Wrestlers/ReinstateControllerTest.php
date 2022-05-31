<?php

use App\Enums\TagTeamStatus;
use App\Enums\WrestlerStatus;
use App\Exceptions\CannotBeReinstatedException;
use App\Http\Controllers\Wrestlers\ReinstateController;
use App\Http\Controllers\Wrestlers\WrestlersController;
use App\Models\TagTeam;
use App\Models\Wrestler;

test('invoke reinstates a suspended wrestler and redirects', function () {
    $wrestler = Wrestler::factory()->suspended()->create();

    $this->actingAs(administrator())
        ->patch(action([ReinstateController::class], $wrestler))
        ->assertRedirect(action([WrestlersController::class, 'index']));

    tap($wrestler->fresh(), function ($wrestler) {
        $this->assertNotNull($wrestler->suspensions->last()->ended_at);
        $this->assertEquals(WrestlerStatus::BOOKABLE, $wrestler->status);
    });
});

test('reinstating a suspended wrestler on an unbookable tag team makes tag team bookable', function () {
    $tagTeam = TagTeam::factory()->withSuspendedWrestler()->create();
    $wrestler = $tagTeam->currentWrestlers()->suspended()->first();

    $this->actingAs(administrator())
        ->patch(action([ReinstateController::class], $wrestler));

    tap($tagTeam->fresh(), function ($tagTeam) {
        $this->assertEquals(TagTeamStatus::BOOKABLE, $tagTeam->status);
    });
});

test('a basic user cannot reinstate a suspended wrestler', function () {
    $wrestler = Wrestler::factory()->suspended()->create();

    $this->actingAs(basicUser())
        ->patch(action([ReinstateController::class], $wrestler))
        ->assertForbidden();
});

test('a guest cannot reinstate a suspended wrestler', function () {
    $wrestler = Wrestler::factory()->suspended()->create();

    $this->patch(action([ReinstateController::class], $wrestler))
        ->assertRedirect(route('login'));
});

test('invoke throws exception for reinstating a non reinstatable wrestler', function ($factoryState) {
    $this->withoutExceptionHandling();

    $wrestler = Wrestler::factory()->{$factoryState}()->create();

    $this->actingAs(administrator())
        ->patch(action([ReinstateController::class], $wrestler));
})->throws(CannotBeReinstatedException::class)->with([
    'bookable',
    'unemployed',
    'injured',
    'released',
    'withFutureEmployment',
    'retired',
]);
