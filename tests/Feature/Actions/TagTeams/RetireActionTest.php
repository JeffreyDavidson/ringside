<?php

use App\Actions\TagTeams\ReinstateAction;
use App\Actions\TagTeams\RetireAction;
use App\Events\TagTeams\TagTeamRetired;
use App\Exceptions\CannotBeRetiredException;
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

test('it retires a bookable tag team at the current datetime by default', function () {
    $tagTeam = TagTeam::factory()->bookable()->create();
    $datetime = now();

    ReinstateAction::shouldNotRun($tagTeam, $datetime);

    $this->tagTeamRepository
        ->shouldReceive('release')
        ->once()
        ->withArgs(function (TagTeam $retiredTagTeam, Carbon $retirementDate) use ($tagTeam, $datetime) {
            assertTrue($retiredTagTeam->is($tagTeam));
            assertTrue($retirementDate->equalTo($datetime));

            return true;
        })
        ->andReturns($tagTeam);

    $this->tagTeamRepository
        ->shouldReceive('retire')
        ->once()
        ->withArgs(function (TagTeam $retiredTagTeam, Carbon $retirementDate) use ($tagTeam, $datetime) {
            assertTrue($retiredTagTeam->is($tagTeam));
            assertTrue($retirementDate->equalTo($datetime));

            return true;
        })
        ->andReturns($tagTeam);

    RetireAction::run($tagTeam);

    Event::assertDispatched(TagTeamRetired::class, function ($event) use ($tagTeam, $datetime) {
        assertTrue($event->tagTeam->is($tagTeam));
        assertTrue($event->retirementDate->is($datetime));

        return true;
    });
});

test('it retires a bookable tag team at a specific datetime', function () {
    $tagTeam = TagTeam::factory()->bookable()->create();
    $datetime = now()->addDays(2);

    ReinstateAction::shouldNotRun($tagTeam, $datetime);

   $this->tagTeamRepository
        ->shouldReceive('release')
        ->once()
        ->with($tagTeam, $datetime)
        ->andReturns($tagTeam);

    $this->tagTeamRepository
        ->shouldReceive('retire')
        ->once()
        ->with($tagTeam, $datetime)
        ->andReturns($tagTeam);

    RetireAction::run($tagTeam, $datetime);

    Event::assertDispatched(TagTeamRetired::class, function ($event) use ($tagTeam, $datetime) {
        assertTrue($event->tagTeam->is($tagTeam));
        assertTrue($event->retirementDate->is($datetime));

        return true;
    });
});

test('it retires a suspended tag team', function () {
    $tagTeam = TagTeam::factory()->suspended()->create();
    $datetime = now();

    ReinstateAction::shouldRun($tagTeam, $datetime);

    $this->tagTeamRepository
        ->shouldReceive('release')
        ->once()
        ->with($tagTeam, $datetime)
        ->andReturns($tagTeam);

    $this->tagTeamRepository
        ->shouldReceive('retire')
        ->once()
        ->with($tagTeam, $datetime)
        ->andReturns($tagTeam);

    RetireAction::run($tagTeam, $datetime);

    Event::assertDispatched(TagTeamRetired::class, function ($event) use ($tagTeam, $datetime) {
        assertTrue($event->tagTeam->is($tagTeam));
        assertTrue($event->retirementDate->is($datetime));

        return true;
    });
});

test('it throws exception for retiring a non retirable tag team', function ($factoryState) {
    $tagTeam = TagTeam::factory()->{$factoryState}()->create();

    RetireAction::run($tagTeam);
})->throws(CannotBeRetiredException::class)->with([
    'retired',
    'withFutureEmployment',
    'unemployed',
]);
