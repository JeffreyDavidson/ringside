<?php

use App\Enums\TagTeamStatus;
use App\Enums\WrestlerStatus;
use App\Exceptions\CannotBeSuspendedException;
use App\Http\Controllers\TagTeams\SuspendController;
use App\Http\Controllers\TagTeams\TagTeamsController;
use App\Models\TagTeam;

test('invoke suspends a tag team and their tag team partners and redirects', function () {
    $tagTeam = TagTeam::factory()->bookable()->create();

    $this->actingAs(administrator())
        ->patch(action([SuspendController::class], $tagTeam))
        ->assertRedirect(action([TagTeamsController::class, 'index']));

    expect($tagTeam->fresh())
        ->suspensions->toHaveCount(1)
        ->status->toBe(TagTeamStatus::SUSPENDED)
        ->currentWrestlers->each(function ($wrestler) {
            $wrestler->status->toBe(WrestlerStatus::SUSPENDED, $wrestler->status);
        });
});

test('a basic user cannot retire a bookable tag team', function () {
    $tagTeam = TagTeam::factory()->bookable()->create();

    $this->actingAs(basicUser())
        ->patch(action([SuspendController::class], $tagTeam))
        ->assertForbidden();
});

test('a guest cannot suspend a bookable tag team', function () {
    $tagTeam = TagTeam::factory()->bookable()->create();

    $this->patch(action([SuspendController::class], $tagTeam))
        ->assertRedirect(route('login'));
});

test('invoke throws exception for retiring a non retirable tag team', function ($factoryState) {
    $this->withoutExceptionHandling();

    $tagTeam = TagTeam::factory()->{$factoryState}()->create();

    $this->actingAs(administrator())
        ->patch(action([SuspendController::class], $tagTeam));
})->throws(CannotBeSuspendedException::class)->with([
    'suspended',
    'unemployed',
    'released',
    'withFutureEmployment',
    'retired',
]);
