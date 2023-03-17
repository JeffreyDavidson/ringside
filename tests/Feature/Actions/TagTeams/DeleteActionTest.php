<?php

use App\Actions\TagTeams\DeleteAction;
use App\Events\TagTeams\TagTeamDeleted;
use App\Models\TagTeam;
use App\Repositories\TagTeamRepository;
use Illuminate\Support\Facades\Event;
use function Pest\Laravel\mock;

beforeEach(function () {
    Event::fake();

    $this->tagTeamRepository = mock(TagTeamRepository::class);
});

test('it deletes a tag team', function () {
    $tagTeam = TagTeam::factory()->create();

    $this->tagTeamRepository
        ->shouldReceive('delete')
        ->once()
        ->with($tagTeam);

    DeleteAction::run($tagTeam);

    Event::assertDispatched(TagTeamDeleted::class, function ($event) use ($tagTeam) {
        expect($event->tagTeam->is($tagTeam))->toBeTrue();

        return true;
    });
});
