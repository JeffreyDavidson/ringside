<?php

use App\Actions\TagTeams\SuspendAction;
use App\Events\TagTeams\TagTeamSuspended;
use App\Exceptions\CannotBeSuspendedException;
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

test('it suspends a bookable tag team at the current datetime by default', function () {
    $tagTeam = TagTeam::factory()->bookable()->create();
    $datetime = now();

    $this->tagTeamRepository
        ->shouldReceive('suspend')
        ->once()
        ->withArgs(function (TagTeam $suspendedTagTeam, Carbon $suspensionDate) use ($tagTeam, $datetime) {
            expect($suspendedTagTeam->is($tagTeam))->toBeTrue();
            expect($suspensionDate->equalTo($datetime))->toBeTrue();

            return true;
        })
        ->andReturns($tagTeam);

    SuspendAction::run($tagTeam);

    Event::assertDispatched(TagTeamSuspended::class, function ($event) use ($tagTeam, $datetime) {
        expect($event->tagTeam->is($tagTeam))->toBeTrue();
        expect($event->suspensionDate->is($datetime))->toBeTrue();

        return true;
    });
});

test('it suspends a bookable tag team at a specific datetime', function () {
    $tagTeam = TagTeam::factory()->bookable()->create();
    $datetime = now()->addDays(2);

    $this->tagTeamRepository
        ->shouldReceive('suspend')
        ->once()
        ->with($tagTeam, $datetime)
        ->andReturns($tagTeam);

    SuspendAction::run($tagTeam, $datetime);

    Event::assertDispatched(TagTeamSuspended::class, function ($event) use ($tagTeam, $datetime) {
        expect($event->tagTeam->is($tagTeam))->toBeTrue();
        expect($event->suspensionDate->is($datetime))->toBeTrue();

        return true;
    });
});

test('it throws exception for suspending a non suspendable tag team', function ($factoryState) {
    $this->withoutExceptionHandling();

    $tagTeam = TagTeam::factory()->{$factoryState}()->create();

    SuspendAction::run($tagTeam);
})->throws(CannotBeSuspendedException::class)->with([
    'unemployed',
    'released',
    'withFutureEmployment',
    'retired',
    'suspended',
]);
