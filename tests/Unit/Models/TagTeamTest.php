<?php

use App\Enums\TagTeamStatus;
use App\Models\TagTeam;

test('a tag team has a name', function () {
    $tagTeam = TagTeam::factory()->create(['name' => 'Example Tag Team Name']);

    expect($tagTeam)->name->toBe('Example Tag Team Name');
});

test('a tag team can have a signature move', function () {
    $tagTeam = TagTeam::factory()->create(['signature_move' => 'Example Signature Move']);

    expect($tagTeam)->signature_move->toBe('Example Signature Move');
});

test('a tag team has a status', function () {
    $tagTeam = TagTeam::factory()->create();

    expect($tagTeam)->status->toBeInstanceOf(TagTeamStatus::class);
});

test('a wrestler belongs to many tag teams', function () {
    $wrestler = Wrestler::factory()->create();
    $tagTeamA = TagTeam::factory()->bookable()->create(['name' => 'Tag Team A']);
    $tagTeamB = TagTeam::factory()->bookable()->create(['name' => 'Tag Team B']);

    $wrestler->tagTeams()->attach($tagTeamA, ['joined_at' => now()]);

    $wrestler->refresh();

    assertCount(1, $wrestler->tagTeams);
    assertCount(0, $wrestler->previousTagTeams);
    assertTrue($wrestler->currentTagTeam->is($tagTeamA));

    $wrestler->tagTeams()->updateExistingPivot($tagTeamA->id, ['left_at' => now()]);

    $wrestler->tagTeams()->attach($tagTeamB, ['joined_at' => now()]);

    $wrestler->refresh();

    assertCount(2, $wrestler->tagTeams);
    assertTrue($wrestler->currentTagTeam->is($tagTeamB));
    assertCount(1, $wrestler->previousTagTeams);
    assertTrue($wrestler->previousTagTeam->is($tagTeamA));
});
