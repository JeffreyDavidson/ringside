<?php

test('clearing an injured wrestler on an unbookable tag team makes tag team bookable', function () {
    $injuredWrestler = Wrestler::factory()
        ->injured()
        ->onCurrentTagTeam($tagTeam = TagTeam::factory()->unbookable()->create())
        ->create();

    expect($tagTeam->fresh())
        ->status->toMatchObject(TagTeamStatus::UNBOOKABLE);

    ClearInjuryAction::run($injuredWrestler);

    expect($tagTeam->fresh())
        ->status->toMatchObject(TagTeamStatus::BOOKABLE);
});
