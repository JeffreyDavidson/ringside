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

test('it reinstates a suspended tag team at the current datetime by default', function () {
    Event::fake();

    testTime()->freeze();
    $tagTeam = TagTeam::factory()->suspended()->create();
    $datetime = now();

    mock(TagTeamRepository::class)
        ->shouldReceive('reinstate')
        ->once()
        ->withArgs(function (TagTeam $suspendedTagTeam, Carbon $reinstatementDate) use ($tagTeam, $datetime) {
            $this->assertTrue($suspendedTagTeam->is($tagTeam));
            $this->assertTrue($reinstatementDate->equalTo($datetime));

            return true;
        })
        ->andReturn($tagTeam);

    ReinstateAction::run($tagTeam);

    Event::assertDispatched(TagTeamReinstated::class);
});

test('it reinstates a suspended tag team at a specific datetime', function () {
    Event::fake();

    testTime()->freeze();
    $tagTeam = TagTeam::factory()->suspended()->create();
    $datetime = now()->addDays(2);

    mock(TagTeamRepository::class)
        ->shouldReceive('reinstate')
        ->once()
        ->with($tagTeam, $datetime)
        ->andReturn($tagTeam);

    ReinstateAction::run($tagTeam, $datetime);

    Event::assertDispatched(TagTeamReinstated::class);
});

test('invoke throws exception for reinstating a non reinstatable tag team', function ($factoryState) {
    $this->withoutExceptionHandling();

    $tagTeam = TagTeam::factory()->{$factoryState}()->create();
    $datetime = now();

    ReinstateAction::run($tagTeam, $datetime);
})->throws(CannotBeReinstatedException::class)->with([
    'bookable',
    'withFutureEmployment',
    'unemployed',
    'released',
    'retired',
]);
