<?php

use App\Actions\TagTeams\CreateAction;
use App\Data\TagTeamData;
use App\Events\TagTeams\TagTeamEmployed;
use App\Models\TagTeam;
use App\Models\Wrestler;
use App\Repositories\TagTeamRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;
use function Pest\Laravel\mock;
use function Spatie\PestPluginTestTime\testTime;

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

    $this->tagTeamRepository
        ->shouldNotReceive('addTagTeamPartner');

    $this->tagTeamRepository
        ->shouldNotReceive('employ');

    CreateAction::run($data);

    Event::assertNotDispatched(TagTeamEmployed::class);
});

test('it can add two wrestlers to a tag team when they are provided', function () {
    [$wrestlerA, $wrestlerB] = Wrestler::factory()->count(2)->create();
    $data = new TagTeamData('Example Tag Team Name', null, null, $wrestlerA, $wrestlerB);
    $datetime = now();

    $this->tagTeamRepository
        ->shouldReceive('create')
        ->once()
        ->with($data)
        ->andReturn($tagTeam = new TagTeam());

    $this->tagTeamRepository
        ->shouldReceive('addTagTeamPartner')
        ->once()
        ->withArgs(function (TagTeam $tagTeamToAddTo, Wrestler $wrestlerAdded, Carbon $joinDate) use ($tagTeam, $wrestlerA, $datetime) {
            expect($tagTeamToAddTo->is($tagTeam))->toBeTrue();
            expect($wrestlerAdded->is($wrestlerA))->toBeTrue();
            expect($joinDate->equalTo($datetime))->toBeTrue();

            return true;
        });

    $this->tagTeamRepository
        ->shouldReceive('addTagTeamPartner')
        ->once()
        ->withArgs(function (TagTeam $tagTeamToAddTo, Wrestler $wrestlerAdded, Carbon $joinDate) use ($tagTeam, $wrestlerB, $datetime) {
            expect($tagTeamToAddTo->is($tagTeam))->toBeTrue();
            expect($wrestlerAdded->is($wrestlerB))->toBeTrue();
            expect($joinDate->equalTo($datetime))->toBeTrue();

            return true;
        });

    $this->tagTeamRepository
        ->shouldNotReceive('employ');

    CreateAction::run($data);

    Event::assertNotDispatched(TagTeamEmployed::class);
});

test('it creates an employment for the tag team with two wrestlers if start date is provided', function () {
    $datetime = now();
    [$wrestlerA, $wrestlerB] = Wrestler::factory()->count(2)->create();
    $data = new TagTeamData('Example Tag Team Name', null, $datetime, $wrestlerA, $wrestlerB);

    $this->tagTeamRepository
        ->shouldReceive('create')
        ->once()
        ->with($data)
        ->andReturn($tagTeam = new TagTeam());

    $this->tagTeamRepository
        ->shouldReceive('addTagTeamPartner')
        ->once()
        ->withArgs(function (TagTeam $tagTeamToAddTo, Wrestler $wrestlerAdded, Carbon $joinDate) use ($tagTeam, $wrestlerA, $datetime) {
            expect($tagTeamToAddTo->is($tagTeam))->toBeTrue();
            expect($wrestlerAdded->is($wrestlerA))->toBeTrue();
            expect($joinDate->equalTo($datetime))->toBeTrue();

            return true;
        });

    $this->tagTeamRepository
        ->shouldReceive('addTagTeamPartner')
        ->once()
        ->withArgs(function (TagTeam $tagTeamToAddTo, Wrestler $wrestlerAdded, Carbon $joinDate) use ($tagTeam, $wrestlerB, $datetime) {
            expect($tagTeamToAddTo->is($tagTeam))->toBeTrue();
            expect($wrestlerAdded->is($wrestlerB))->toBeTrue();
            expect($joinDate->equalTo($datetime))->toBeTrue();

            return true;
        });

    $this->tagTeamRepository
        ->shouldReceive('employ')
        ->with($tagTeam, $datetime);

    CreateAction::run($data);

    Event::assertDispatched(TagTeamEmployed::class, function ($event) use ($tagTeam, $datetime) {
        expect($event->tagTeam->is($tagTeam))->toBeTrue();
        expect($event->employmentDate->is($datetime))->toBeTrue();

        return true;
    });
});
