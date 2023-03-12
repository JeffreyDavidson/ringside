<?php

use App\Actions\TagTeams\ReinstateAction;
use App\Events\TagTeams\TagTeamReinstated;
use App\Exceptions\CannotBeReinstatedException;
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

test('it reinstates a suspended tag team at the current datetime by default', function () {
    $tagTeam = TagTeam::factory()->suspended()->create();
    $datetime = now();

    $this->tagTeamRepository
        ->shouldReceive('reinstate')
        ->once()
        ->withArgs(function (TagTeam $reinstatedTagTeam, Carbon $reinstatementDate) use ($tagTeam, $datetime) {
            assertTrue($reinstatedTagTeam->is($tagTeam));
            assertTrue($reinstatementDate->equalTo($datetime));

            return true;
        })
        ->andReturn($tagTeam);

    ReinstateAction::run($tagTeam);

    Event::assertDispatched(TagTeamReinstated::class, function ($event) use ($tagTeam, $datetime) {
        assertTrue($event->tagTeam->is($tagTeam));
        assertTrue($event->reinstatementDate->is($datetime));

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
        assertTrue($event->tagTeam->is($tagTeam));
        assertTrue($event->reinstatementDate->is($datetime));

        return true;
    });
});

test('invoke throws exception for reinstating a non reinstatable tag team', function ($factoryState) {
    $this->withoutExceptionHandling();

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
