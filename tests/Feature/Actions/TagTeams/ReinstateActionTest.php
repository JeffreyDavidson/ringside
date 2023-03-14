<?php

use App\Actions\TagTeams\ReinstateAction;
use App\Events\TagTeams\TagTeamReinstated;
use App\Exceptions\CannotBeReinstatedException;
use App\Models\TagTeam;
use App\Repositories\TagTeamRepository;
use function Pest\Laravel\mock;
use function Spatie\PestPluginTestTime\testTime;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;

beforeEach(function () {
    Event::fake();

    testTime()->freeze();

    $this->tagTeamRepository = mock(TagTeamRepository::class);
});

test('it reinstates a suspended tag team at the current datetime by default', function () {
    $tagTeam = TagTeam::factory()->suspended()->create();
    $datetime = now();

    $this->tagTeamRepository
        ->shouldReceive('reinstate')
        ->once()
        ->withArgs(function (TagTeam $reinstatedTagTeam, Carbon $reinstatementDate) use ($tagTeam, $datetime) {
            expect($reinstatedTagTeam->is($tagTeam))->toBeTrue();
            expect($reinstatementDate->equalTo($datetime))->toBeTrue();

            return true;
        })
        ->andReturn($tagTeam);

    ReinstateAction::run($tagTeam);

    Event::assertDispatched(TagTeamReinstated::class, function ($event) use ($tagTeam, $datetime) {
        expect($event->tagTeam->is($tagTeam))->toBeTrue();
        expect($event->reinstatementDate->is($datetime))->toBeTrue();

        return true;
    });
});

test('it reinstates a suspended tag team at a specific datetime', function () {
    $tagTeam = TagTeam::factory()->suspended()->create();
    $datetime = now()->addDays(2);

    $this->tagTeamRepository
        ->shouldReceive('reinstate')
        ->once()
        ->with($tagTeam, $datetime)
        ->andReturn($tagTeam);

    ReinstateAction::run($tagTeam, $datetime);

    Event::assertDispatched(TagTeamReinstated::class, function ($event) use ($tagTeam, $datetime) {
        expect($event->tagTeam->is($tagTeam))->toBeTrue();
        expect($event->reinstatementDate->is($datetime))->toBeTrue();

        return true;
    });
});

test('invoke throws exception for reinstating a non reinstatable tag team', function ($factoryState) {
    $tagTeam = TagTeam::factory()->{$factoryState}()->create();
    $datetime = now();

    ReinstateAction::run($tagTeam, $datetime);
})->throws(CannotBeReinstatedException::class)->with([
    'bookable',
    'unbookable',
    'withFutureEmployment',
    'unemployed',
    'released',
    'retired',
]);
