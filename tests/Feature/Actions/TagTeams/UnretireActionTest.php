<?php

use App\Actions\TagTeams\UnretireAction;
use App\Events\TagTeams\TagTeamUnretired;
use App\Exceptions\CannotBeUnretiredException;
use App\Models\TagTeam;
use App\Repositories\TagTeamRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;
use function Pest\Laravel\mock;
use function PHPUnit\Framework\assertTrue;
use function Spatie\PestPluginTestTime\testTime;

beforeEach(function () {
    Event::fake();

    testTime()->freeze();

    $this->tagTeamRepository = mock(TagTeamRepository::class);
});

test('it unretires a retired tag team at the current datetime by default', function () {
    $tagTeam = TagTeam::factory()->retired()->create();
    $datetime = now();

    $this->tagTeamRepository
        ->shouldReceive('unretire')
        ->once()
        ->withArgs(function (TagTeam $unretiredTagTeam, Carbon $unretireDate) use ($tagTeam, $datetime) {
            assertTrue($unretiredTagTeam->is($tagTeam));
            assertTrue($unretireDate->equalTo($datetime));

            return true;
        })
        ->andReturns($tagTeam);

    UnretireAction::run($tagTeam);

    Event::assertDispatched(TagTeamUnretired::class, function ($event) use ($tagTeam, $datetime) {
        assertTrue($event->tagTeam->is($tagTeam));
        assertTrue($event->unretireDate->is($datetime));

        return true;
    });
});

test('it unretires a retired tag team at a specific datetime', function () {
    $tagTeam = TagTeam::factory()->retired()->create();
    $datetime = now()->addDays(2);

    $this->tagTeamRepository
        ->shouldReceive('unretire')
        ->once()
        ->with($tagTeam, $datetime)
        ->andReturns($tagTeam);

    UnretireAction::run($tagTeam, $datetime);

    Event::assertDispatched(TagTeamUnretired::class, function ($event) use ($tagTeam, $datetime) {
        assertTrue($event->tagTeam->is($tagTeam));
        assertTrue($event->unretireDate->is($datetime));

        return true;
    });
});

test('it throws exception for unretiring a non retired tag team', function ($factoryState) {
    $this->withoutExceptionHandling();

    $tagTeam = TagTeam::factory()->{$factoryState}()->create();

    UnretireAction::run($tagTeam);
})->throws(CannotBeUnretiredException::class)->with([
    'bookable',
    'withFutureEmployment',
    'released',
    'suspended',
    'unemployed',
    'unbookable',
]);
