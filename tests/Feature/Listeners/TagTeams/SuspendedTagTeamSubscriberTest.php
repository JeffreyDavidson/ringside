<?php

use App\Actions\TagTeams\SuspendAction;
use App\Enums\TagTeamStatus;
use App\Enums\WrestlerStatus;
use App\Models\TagTeam;
use App\Models\Wrestler;

test('invoke suspends a tag team and their tag team partners and redirects', function () {
    [$wrestlerA, $wrestlerB] = Wrestler::factory()->bookable()->count(2)->create();
    $tagTeam = TagTeam::factory()
        ->hasAttached($wrestlerA, ['joined_at' => now()->toDateTimeString()])
        ->hasAttached($wrestlerB, ['joined_at' => now()->toDateTimeString()])
        ->bookable()
        ->create();

    SuspendAction::run($tagTeam);

    expect($tagTeam->fresh())
        ->suspensions->toHaveCount(1)
        ->status->toMatchObject(TagTeamStatus::SUSPENDED)
        ->currentWrestlers->each(function ($wrestler) {
            $wrestler->status->toMatchObject(WrestlerStatus::SUSPENDED);
        });
});
