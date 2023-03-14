<?php

use App\Actions\TagTeams\AddTagTeamPartnerAction;
use App\Actions\TagTeams\CreateAction;
use App\Actions\TagTeams\EmployAction;
use App\Data\TagTeamData;
use App\Events\TagTeams\TagTeamEmployed;
use App\Models\TagTeam;
use App\Models\Wrestler;
use App\Repositories\TagTeamRepository;
use function Pest\Laravel\mock;
use function Spatie\PestPluginTestTime\testTime;
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

    AddTagTeamPartnerAction::shouldNotRun();
    EmployAction::shouldNotRun();

    CreateAction::run($data);

    Event::assertNotDispatched(TagTeamEmployed::class);
});

test('it can add two wrestlers to a tag team when they are provided', function () {
    $wrestlerA = Wrestler::factory()->create();
    $wrestlerB = Wrestler::factory()->create();
    $data = new TagTeamData('Example Tag Team Name', null, null, $wrestlerA, $wrestlerB);

    $this->tagTeamRepository
        ->shouldReceive('create')
        ->once()
        ->with($data)
        ->andReturn($tagTeam = new TagTeam());

    AddTagTeamPartnerAction::shouldRun()->once()->with($tagTeam, $wrestlerA);
    AddTagTeamPartnerAction::shouldRun()->once()->with($tagTeam, $wrestlerB);

    EmployAction::shouldNotRun();

    CreateAction::run($data);

    Event::assertNotDispatched(TagTeamEmployed::class);
});

test('it creates an employment for the tag team with two wrestlers if start date is provided', function () {
    $datetime = now();
    $wrestlerA = Wrestler::factory()->create();
    $wrestlerB = Wrestler::factory()->create();
    $data = new TagTeamData('Example Tag Team Name', null, $datetime, $wrestlerA, $wrestlerB);

    $this->tagTeamRepository
        ->shouldReceive('create')
        ->once()
        ->with($data)
        ->andReturn($tagTeam = new TagTeam());

    AddTagTeamPartnerAction::shouldRun()->with($tagTeam, $wrestlerA);
    AddTagTeamPartnerAction::shouldRun($tagTeam, $wrestlerB);
    EmployAction::shouldRun($tagTeam, $datetime);

    CreateAction::run($data);

    Event::assertDispatched(TagTeamEmployed::class, function ($event) use ($tagTeam, $datetime) {
        expect($event->tagTeam->is($tagTeam))->toBeTrue();
        expect($event->employmentDate->is($datetime))->toBeTrue();

        return true;
    });
});
