<?php

test('releasing a bookable wrestler on a bookable tag team makes tag team unbookable', function () {
    $this->withoutExceptionHandling();

    $tagTeam = TagTeam::factory()->bookable()->create();
    $wrestler = $tagTeam->currentWrestlers()->first();

    $this->actingAs(administrator())
        ->patch(action([ReleaseController::class], $wrestler))
        ->assertRedirect(action([WrestlersController::class, 'index']));

    expect($tagTeam->fresh())
        ->status->toMatchObject(TagTeamStatus::UNBOOKABLE);
})->skip();
