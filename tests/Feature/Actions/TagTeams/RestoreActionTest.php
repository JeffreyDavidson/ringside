<?php

use App\Actions\TagTeams\RestoreAction;
use App\Exceptions\CannotJoinTagTeamException;
use App\Models\TagTeam;
use App\Models\Wrestler;
use App\Repositories\TagTeamRepository;
use function Pest\Laravel\mock;
use Illuminate\Support\Facades\Event;

beforeEach(function () {
    Event::fake();

    $this->tagTeamRepository = mock(TagTeamRepository::class);
});

test('it restores a deleted tag team', function () {
    $tagTeam = TagTeam::factory()->trashed()->create();

    $this->tagTeamRepository
        ->shouldReceive('restore')
        ->once()
        ->with($tagTeam);

    RestoreAction::run($tagTeam);
});

test('it throws exception for restoring a wrestler on a current tag team from their original deleted tag team', function () {
    [$wrestlerA, $wrestlerB, $wrestlerC] = Wrestler::factory()->count(3)->create();
    $datetime = now();
    $wrestlerOldTagTeam = TagTeam::factory()
        ->withPreviousWrestlers([$wrestlerA, $wrestlerB], $datetime)
        ->trashed()
        ->create();

    TagTeam::factory()
        ->withCurrentWrestlers([$wrestlerA, $wrestlerC], $datetime)->bookable()->create();

    RestoreAction::run($wrestlerOldTagTeam);
})->throws(CannotJoinTagTeamException::class);
