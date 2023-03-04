<?php

use App\Actions\TagTeams\DeleteAction;
use App\Events\TagTeams\TagTeamDeleted;
use App\Models\TagTeam;
use App\Repositories\TagTeamRepository;
use function Pest\Laravel\mock;
use Illuminate\Support\Facades\Event;

test('it deletes a tag team', function () {
    Event::fake();

    $tagTeam = TagTeam::factory()->create();

    mock(TagTeamRepository::class)
        ->shouldReceive('delete')
        ->once()
        ->with($tagTeam);

    DeleteAction::run($tagTeam);

    Event::assertDispatched(TagTeamDeleted::class);
});
