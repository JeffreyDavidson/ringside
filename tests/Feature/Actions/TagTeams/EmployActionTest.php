<?php

use App\Actions\TagTeams\EmployAction;
use App\Events\TagTeams\TagTeamEmployed;
use App\Exceptions\CannotBeEmployedException;
use App\Models\TagTeam;
use App\Repositories\TagTeamRepository;
use function Pest\Laravel\mock;
use function PHPUnit\Framework\assertTrue;
use function Spatie\PestPluginTestTime\testTime;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;

beforeEach(function () {
    Event::fake();

    testTime()->freeze();

    $this->tagTeamRepository = mock(TagTeamRepository::class);
});

test('it employs an employable tag team at the current datetime by default', function ($factoryState) {
    $tagTeam = TagTeam::factory()->{$factoryState}()->create();
    $datetime = now();

    $this->tagTeamRepository
        ->shouldReceive('employ')
        ->once()
        ->withArgs(function (TagTeam $employedTagTeam, Carbon $employmentDate) use ($tagTeam, $datetime) {
            assertTrue($employedTagTeam->is($tagTeam));
            assertTrue($employmentDate->equalTo($datetime));

            return true;
        })
        ->andReturns($tagTeam);

    EmployAction::run($tagTeam);

    Event::assertDispatched(TagTeamEmployed::class, function ($event) use ($tagTeam, $datetime) {
        assertTrue($event->tagTeam->is($tagTeam));
        assertTrue($event->employmentDate->is($datetime));

        return true;
    });
})->with([
    'unemployed',
    'released',
    'retired',
    'withFutureEmployment',
]);

test('it employs an employable tag team at a specific datetime', function ($factoryState) {
    $tagTeam = TagTeam::factory()->{$factoryState}()->create();
    $datetime = now()->addDays(2);

    $this->tagTeamRepository
        ->shouldReceive('employ')
        ->once()
        ->with($tagTeam, $datetime)
        ->andReturns($tagTeam);

    EmployAction::run($tagTeam, $datetime);

    Event::assertDispatched(TagTeamEmployed::class, function ($event) use ($tagTeam, $datetime) {
        assertTrue($event->tagTeam->is($tagTeam));
        assertTrue($event->employmentDate->is($datetime));

        return true;
    });
})->with([
    'unemployed',
    'released',
    'retired',
    'withFutureEmployment',
]);

test('it throws exception for employing a non employable tag team', function ($factoryState) {
    $this->withoutExceptionHandling();

    $tagTeam = TagTeam::factory()->{$factoryState}()->create();

    EmployAction::run($tagTeam);
})->throws(CannotBeEmployedException::class)->with([
    'bookable',
    'unbookable',
    'suspended',
]);
