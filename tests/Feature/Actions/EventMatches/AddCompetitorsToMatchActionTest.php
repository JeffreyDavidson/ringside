<?php

use App\Actions\EventMatches\AddCompetitorsToMatchAction;
use App\Actions\EventMatches\AddTagTeamsToMatchAction;
use App\Actions\EventMatches\AddWrestlersToMatchAction;
use App\Models\EventMatch;
use App\Models\EventMatchCompetitor;
use App\Models\TagTeam;
use App\Models\Wrestler;
use Database\Seeders\MatchTypesTableSeeder;
use Illuminate\Support\Arr;

beforeEach(function () {
    $this->seed(MatchTypesTableSeeder::class);
});

test('it adds wrestler competitors to a match', function () {
    $eventMatch = EventMatch::factory()->create();
    [$wrestlerA, $wrestlerB] = Wrestler::factory()->count(2)->create();
    $competitors = collect([
        0 => [
            'wrestlers' => collect([$wrestlerA]),
        ],
        1 => [
            'wrestlers' => collect([$wrestlerB]),
        ],
    ]);

    AddWrestlersToMatchAction::shouldRun()
        ->with($eventMatch, Arr::get($competitors, 'wrestlers'), $competitors->count())
        ->times(count(Arr::get($competitors, 'wrestlers')));

    AddTagTeamsToMatchAction::shouldNotRun();

    AddCompetitorsToMatchAction::run($eventMatch, $competitors);
});

test('it adds tag team competitors to a match', function () {
    $eventMatch = EventMatch::factory()->create();
    $tagTeamA = TagTeam::factory()->create();
    $tagTeamB = TagTeam::factory()->create();
    $competitors = collect([
        0 => [
            'tag_teams' => collect([$tagTeamA]),
        ],
        1 => [
            'tag_teams' => collect([$tagTeamB]),
        ],
    ]);

    AddWrestlersToMatchAction::shouldNotRun();

    AddTagTeamsToMatchAction::shouldRun()
        ->with($eventMatch, Arr::get($competitors, 'tag_teams'), $competitors->count())
        ->times(count(Arr::get($competitors, 'tag_teams')));

    AddCompetitorsToMatchAction::run($eventMatch, $competitors);
});
