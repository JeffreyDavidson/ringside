<?php

use App\Enums\TagTeamStatus;
use App\Enums\WrestlerStatus;

test('invoke unretires a retired tag team and its tag team partners and redirects', function () {
    $this->actingAs(administrator())
        ->patch(action([UnretireController::class], $this->tagTeam))
        ->assertRedirect(action([TagTeamsController::class, 'index']));

    expect($this->tagTeam->fresh())
        ->retirements->last()->ended_at->not->toBeNull()
        ->status->toMatchObject(TagTeamStatus::BOOKABLE)
        ->currentWrestlers->each(function ($wrestler) {
            $wrestler->retirements->last()->ended_at->not->toBeNull()
                ->status->toMatchObject(WrestlerStatus::BOOKABLE);
        });
});
