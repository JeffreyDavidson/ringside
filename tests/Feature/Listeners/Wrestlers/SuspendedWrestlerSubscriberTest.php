<?php

test('suspending a bookable wrestler on a bookable tag team makes tag team unbookable', function () {
    $tagTeam = TagTeam::factory()->bookable()->create();
    $wrestler = $tagTeam->currentWrestlers()->first();

    $this->actingAs(administrator())
        ->patch(action([SuspendController::class], $wrestler));

    expect($tagTeam->fresh())
        ->status->toMatchObject(TagTeamStatus::UNBOOKABLE);
})->skip();
