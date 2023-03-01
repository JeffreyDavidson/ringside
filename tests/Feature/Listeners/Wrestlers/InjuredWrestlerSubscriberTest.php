<?php

test('injuring a bookable wrestler on a bookable tag team makes tag team unbookable', function () {

    $tagTeam = TagTeam::factory()->bookable()->create();
    $wrestler = $tagTeam->currentWrestlers()->first();

    InjureAction::run($wrestler);

    expect($wrestler->currentTagTeam->fresh())
        ->status->toMatchObject(TagTeamStatus::UNBOOKABLE);
})->skip();
