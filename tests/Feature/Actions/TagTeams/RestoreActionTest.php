<?php

use App\Actions\TagTeams\RestoreAction;
use App\Models\TagTeam;
use App\Models\Wrestler;
use App\Repositories\TagTeamRepository;
use function Pest\Laravel\mock;

test('it restores a deleted tag team', function () {
    $tagTeam = TagTeam::factory()->trashed()->create();

    mock(TagTeamRepository::class)
        ->shouldReceive('restore')
        ->once()
        ->with($tagTeam);

    RestoreAction::run($tagTeam);
})->skip();

test('it throws exception for restoring a wrestler on a current tag team from their original deleted tag team', function () {
    $wrestlerA = Wrestler::factory()->create(['name' => 'Example Name A']);
    $wrestlerB = Wrestler::factory()->create(['name' => 'Example Name B']);
    $wrestlerOldTagTeam = TagTeam::factory()->withCurrentWrestler($wrestlerA)->withCurrentWrestler($wrestlerB)->bookable()->create();

    $wrestlerNewTagTeam = TagTeam::factory()->withCurrentWrestler($wrestler)->bookable()->create();

    mock(TagTeamRepository::class)
        ->shouldNotReceive('restore')
        ->once()
        ->with($wrestlerOldTagTeam);

    RestoreAction::run($wrestlerOldTagTeam);
})->throws(Exception::class);
