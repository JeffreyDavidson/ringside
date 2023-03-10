<?php

use App\Builders\TagTeamQueryBuilder;
use App\Enums\TagTeamStatus;
use App\Models\TagTeam;
use App\Models\Wrestler;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Sequence;

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

test('a tag team has a dedicated query builder', function () {
    expect(TagTeam::query())->toBeInstanceOf(TagTeamQueryBuilder::class);
});

test('a tag team has many wrestlers', function () {
    [$wrestlerA, $wrestlerB] = Wrestler::factory()->count(2)->create();
    [$wrestlerC, $wrestlerD] = Wrestler::factory()->count(2)->create();
    $tagTeam = TagTeam::factory()
        ->withCurrentWrestlers([$wrestlerA, $wrestlerB])
        ->withPreviousWrestlers([$wrestlerC, $wrestlerD])
        ->create();

    expect(TagTeam::factory()->create()->wrestlers)->toBeInstanceOf(Collection::class);
    expect($tagTeam->wrestlers)->toHaveCount(4);

    expect($tagTeam->fresh()->currentWrestlers)->toHaveCount(2);
    expect($tagTeam->fresh()->currentWrestlers)->collectionHas($wrestlerA);
    expect($tagTeam->fresh()->currentWrestlers)->collectionHas($wrestlerB);

    expect($tagTeam->fresh()->previousWrestlers)->toHaveCount(2);
    expect($tagTeam->fresh()->previousWrestlers)->collectionHas($wrestlerC);
    expect($tagTeam->fresh()->previousWrestlers)->collectionHas($wrestlerD);
});

test('it can get the combined weight of the current wrestlers on a tag team', function () {
    [$wrestlerA, $wrestlerB] = Wrestler::factory()
        ->count(2)
        ->state(new Sequence(
            ['weight' => 240],
            ['weight' => 180],
        ))
        ->create();
    $tagTeam = TagTeam::factory()
        ->withCurrentWrestlers([$wrestlerA, $wrestlerB])
        ->create();

    expect($tagTeam)->combinedWeight->toEqual(420);
});

test('a tag team can compete in event matches', function () {
    expect(TagTeam::factory()->create()->eventMatches)->toBeInstanceOf(Collection::class);
});
