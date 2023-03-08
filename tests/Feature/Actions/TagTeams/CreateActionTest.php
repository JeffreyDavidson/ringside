<?php

use App\Actions\TagTeams\CreateAction;
use App\Data\TagTeamData;
use App\Models\TagTeam;
use App\Models\Wrestler;
use App\Repositories\TagTeamRepository;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;
use function Spatie\PestPluginTestTime\testTime;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;

beforeEach(function () {
    Event::fake();

    testTime()->freeze();

    $this->tagTeamRepository = mock(TagTeamRepository::class);
});

test('it creates a tag team', function () {
    $data = new TagTeamData('Example Tag Team Name', null, null, null, null);

    $this->tagTeamRepository
        ->shouldReceive('create')
        ->once()
        ->with($data)
        ->andReturn(new TagTeam());

    CreateAction::run($data);
});
