<?php

test('reinstating a suspended wrestler on an unbookable tag team makes tag team bookable', function () {
    $tagTeam = TagTeam::factory()
        ->hasAttached($suspendedWrestler = Wrestler::factory()->suspended()->create())
        ->hasAttached(Wrestler::factory()->bookable())
        ->has(Employment::factory()->started(Carbon::yesterday()))
        ->create();

    $this->actingAs(administrator())
        ->patch(action([ReinstateController::class], $suspendedWrestler));

    expect($tagTeam->fresh())
        ->status->toMatchObject(TagTeamStatus::BOOKABLE);
})->skip();
