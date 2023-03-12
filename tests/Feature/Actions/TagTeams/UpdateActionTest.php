<?php

use App\Actions\TagTeams\UpdateAction;
use App\Data\TagTeamData;
use App\Models\TagTeam;
use App\Repositories\TagTeamRepository;
use Illuminate\Support\Facades\Event;
use function Pest\Laravel\mock;

beforeEach(function () {
    Event::fake();

    $this->tagTeamRepository = mock(TagTeamRepository::class);
});

test('it updates a tag team', function () {
    $tagTeam = TagTeam::factory()->create(['name' => 'Old Tag Team Name']);
    $data = new TagTeamData('New Example Tag Team Name', null, null, null, null);

    $this->tagTeamRepository
        ->shouldReceive('update')
        ->once()
        ->with($tagTeam, $data)
        ->andReturn($tagTeam);

    UpdateAction::run($tagTeam, $data);
});
