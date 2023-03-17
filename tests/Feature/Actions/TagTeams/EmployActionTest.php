<?php

use App\Actions\TagTeams\EmployAction;
use App\Events\TagTeams\TagTeamEmployed;
use App\Exceptions\CannotBeEmployedException;
use App\Models\TagTeam;
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

test('it employs an employable tag team at the current datetime by default', function ($factoryState) {
    $tagTeam = TagTeam::factory()->{$factoryState}()->create();
    $datetime = now();

    $this->tagTeamRepository
        ->shouldNotReceive('unretire');

    $this->tagTeamRepository
        ->shouldReceive('employ')
        ->once()
        ->withArgs(function (TagTeam $employedTagTeam, Carbon $employmentDate) use ($tagTeam, $datetime) {
            expect($employedTagTeam->is($tagTeam))->toBeTrue();
            expect($employmentDate->equalTo($datetime))->toBeTrue();

            return true;
        })
        ->andReturns($tagTeam);

    EmployAction::run($tagTeam);

    Event::assertDispatched(TagTeamEmployed::class, function ($event) use ($tagTeam, $datetime) {
        expect($event->tagTeam->is($tagTeam))->toBeTrue();
        expect($event->employmentDate->is($datetime))->toBeTrue();

        return true;
    });
})->with([
    'unemployed',
    'released',
    'withFutureEmployment',
]);

test('it employs an employable tag team at a specific datetime', function ($factoryState) {
    $tagTeam = TagTeam::factory()->{$factoryState}()->create();
    $datetime = now()->addDays(2);

    $this->tagTeamRepository
        ->shouldNotReceive('unretire');

    $this->tagTeamRepository
        ->shouldReceive('employ')
        ->once()
        ->with($tagTeam, $datetime)
        ->andReturns($tagTeam);

    EmployAction::run($tagTeam, $datetime);

    Event::assertDispatched(TagTeamEmployed::class, function ($event) use ($tagTeam, $datetime) {
        expect($event->tagTeam->is($tagTeam))->toBeTrue();
        expect($event->employmentDate->is($datetime))->toBeTrue();

        return true;
    });
})->with([
    'unemployed',
    'released',
    'withFutureEmployment',
]);

test('it employs a retired tag team at the current datetime by default', function () {
    $tagTeam = TagTeam::factory()->retired()->create();
    $datetime = now();

    $this->tagTeamRepository
        ->shouldReceive('unretire')
        ->withArgs(function (TagTeam $retiredTagTeam, Carbon $unretireDate) use ($tagTeam, $datetime) {
            expect($retiredTagTeam->is($tagTeam))->toBeTrue();
            expect($unretireDate->equalTo($datetime))->toBeTrue();

            return true;
        })
        ->once()
        ->andReturn($tagTeam);

    $this->tagTeamRepository
        ->shouldReceive('employ')
        ->once()
        ->withArgs(function (TagTeam $employedTagTeam, Carbon $employmentDate) use ($tagTeam, $datetime) {
            expect($employedTagTeam->is($tagTeam))->toBeTrue();
            expect($employmentDate->equalTo($datetime))->toBeTrue();

            return true;
        })
        ->andReturns($tagTeam);

    EmployAction::run($tagTeam);

    Event::assertDispatched(TagTeamEmployed::class, function ($event) use ($tagTeam, $datetime) {
        expect($event->tagTeam->is($tagTeam))->toBeTrue();
        expect($event->employmentDate->is($datetime))->toBeTrue();

        return true;
    });
});

test('it employs a retired tag team at a specific datetime', function () {
    $tagTeam = TagTeam::factory()->retired()->create();
    $datetime = now()->addDays(2);

    $this->tagTeamRepository
        ->shouldReceive('unretire')
        ->with($tagTeam, $datetime)
        ->once()
        ->andReturn($tagTeam);

    $this->tagTeamRepository
        ->shouldReceive('employ')
        ->once()
        ->with($tagTeam, $datetime)
        ->andReturns($tagTeam);

    EmployAction::run($tagTeam, $datetime);

    Event::assertDispatched(TagTeamEmployed::class, function ($event) use ($tagTeam, $datetime) {
        expect($event->tagTeam->is($tagTeam))->toBeTrue();
        expect($event->employmentDate->is($datetime))->toBeTrue();

        return true;
    });
});

test('it throws exception for employing a non employable tag team', function ($factoryState) {
    $tagTeam = TagTeam::factory()->{$factoryState}()->create();

    EmployAction::run($tagTeam);
})->throws(CannotBeEmployedException::class)->with([
    'bookable',
    'unbookable',
    'suspended',
]);
