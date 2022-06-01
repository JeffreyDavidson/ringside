<?php

use App\Enums\StableStatus;
use App\Enums\TagTeamStatus;
use App\Enums\WrestlerStatus;
use App\Exceptions\CannotBeDeactivatedException;
use App\Http\Controllers\Stables\DeactivateController;
use App\Http\Controllers\Stables\StablesController;
use App\Models\Stable;

test('invoke deactivates an active stable and its members and redirects', function () {
    $stable = Stable::factory()->unactivated()->withUnemployedDefaultMembers()->create();

    $this->actAs(administrator())
        ->patch(action([DeactivateController::class], $stable))
        ->assertRedirect(action([StablesController::class, 'index']));

        tap($stable->fresh(), function ($stable) {
            $this->assertNotNull($stable->activations->last()->ended_at);
            $this->assertEquals(StableStatus::INACTIVE, $stable->status);

            foreach ($stable->currentWrestlers as $wrestler) {
                $this->assertEquals(WrestlerStatus::RELEASED, $wrestler->status);
            }

            foreach ($stable->currentTagTeams as $tagTeam) {
                $this->assertEquals(TagTeamStatus::RELEASED, $tagTeam->status);
            }
        });

    expect($stable->fresh())
        ->activations->last()->ended_at->not->toBeNull()
        ->status->toBe(StableStatus::INACTIVE)
        ->currentWrestlers->each(fn ($wrestler) => $wrestler->status->toBe(WrestlerStatus::RELEASED))
        ->currentTagTeams->each(fn ($tagTeam) => $tagTeam->status->toBe(TagTeamStatus::RELEASED));
});

test('a basic user cannot deactivate a stable', function () {
    $stable = Stable::factory()->active()->create();

    $this->actAs(basicUser())
        ->patch(action([DeactivateController::class], $stable))
        ->assertForbidden();
});

test('a guest cannot activate a stable', function () {
    $stable = Stable::factory()->active()->create();

    $this->patch(action([DeactivateController::class], $stable))
        ->assertRedirect(route('login'));
});

test('invoke throws exception for deactivating a non deactivatable stable', function ($factoryState) {
    $this->withoutExceptionHandling();

    $stable = Stable::factory()->{$factoryState}()->create();

    $this->actingAs(administrator())
        ->patch(action([ActivateController::class], $stable));
})->throws(CannotBeDeactivatedException::class)->with([
    'iactive',
    'retired',
    'unactivated',
    'withFutureActivation',
]);
