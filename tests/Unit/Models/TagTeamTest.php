<?php

use App\Builders\TagTeamQueryBuilder;
use App\Enums\TagTeamStatus;
use App\Models\EventMatch;
use App\Models\TagTeam;
use App\Models\Wrestler;
use Illuminate\Database\Eloquent\Collection;

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

test('a tag team has a dedicated query builder', function () {
    expect(TagTeam::query())->toBeInstanceOf(TagTeamQueryBuilder::class);
});

test('a tag team has wrestlers', function () {
    expect(TagTeam::factory()->create()->wrestlers)->toBeInstanceOf(Collection::class);
});

test('it can get the current wrestlers on a tag team', function () {
    $tagTeam = TagTeam::factory()
        ->hasAttached($wrestlerA = Wrestler::factory()->create())
        ->hasAttached($wrestlerB = Wrestler::factory()->create())
        ->create();

    expect($tagTeam->fresh()->currentWrestlers)->collectionHas($wrestlerA);
    expect($tagTeam->fresh()->currentWrestlers)->collectionHas($wrestlerB);
});

test('it can get the previous wrestlers on a tag team', function () {
    $now = now();
    $tagTeam = TagTeam::factory()
        ->hasAttached(
            $wrestlerA = Wrestler::factory()->create(),
            ['joined_at' => $now->subDays(3)->toDateTimeString(), 'left_at' => $now->copy()->toDateTimeString()]
        )
        ->hasAttached(
            $wrestlerB = Wrestler::factory()->create(),
            ['joined_at' => $now->subDays(3)->toDateTimeString(), 'left_at' => $now->copy()->toDateTimeString()]
        )
        ->hasAttached(
            $wrestlerC = Wrestler::factory()->create(),
            ['joined_at' => $now->copy()->toDateTimeString(), 'left_at' => null]
        )
        ->hasAttached(
            $wrestlerD = Wrestler::factory()->create(),
            ['joined_at' => $now->copy()->toDateTimeString(), 'left_at' => null]
        )
        ->create();

    expect($tagTeam->fresh()->previousWrestlers)->toHaveCount(2);
    expect($tagTeam->fresh()->previousWrestlers)->collectionHas($wrestlerA);
    expect($tagTeam->fresh()->previousWrestlers)->collectionHas($wrestlerB);
    expect($tagTeam->fresh()->previousWrestlers)->collectionDoesntHave($wrestlerC);
    expect($tagTeam->fresh()->previousWrestlers)->collectionDoesntHave($wrestlerD);
});

test('it can get the combined weight of the current wrestlers on a tag team', function () {
    $tagTeam = TagTeam::factory()
        ->hasAttached(Wrestler::factory()->create(['weight' => 240]))
        ->hasAttached(Wrestler::factory()->create(['weight' => 180]))
        ->create();

    expect($tagTeam)->combinedWeight->toEqual(420);
});

test('it can get the the event matches for a tag team', function () {
    $eventMatch = EventMatch::factory()->tagTeamType()->create();

    expect($tagTeam->eventMatches)->dd()->collectionHas($eventMatch);
});
