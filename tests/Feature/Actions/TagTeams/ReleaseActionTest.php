<?php

use App\Actions\TagTeams\ReleaseAction;
use App\Events\TagTeams\TagTeamReleased;
use App\Exceptions\CannotBeReleasedException;
use App\Models\TagTeam;
use App\Repositories\TagTeamRepository;
use function Pest\Laravel\mock;
use function Spatie\PestPluginTestTime\testTime;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;

test('it releases an bookable tag team at the current datetime by default', function () {
    Event::fake();

    testTime()->freeze();
    $tagTeam = TagTeam::factory()->bookable()->create();
    $datetime = now();

    mock(TagTeamRepository::class)
        ->shouldReceive('release')
        ->once()
        ->withArgs(function (TagTeam $releasedTagTeam, Carbon $releaseDate) use ($tagTeam, $datetime) {
            $this->assertTrue($releasedTagTeam->is($tagTeam));
            $this->assertTrue($releaseDate->equalTo($datetime));

            return true;
        })
        ->andReturn($tagTeam);

    ReleaseAction::run($tagTeam);

    Event::assertDispatched(TagTeamReleased::class);
});

test('it releases an bookable tag team at a specific datetime', function () {
    Event::fake();

    testTime()->freeze();
    $tagTeam = TagTeam::factory()->bookable()->create();
    $datetime = now()->addDays(2);

    mock(TagTeamRepository::class)
        ->shouldReceive('release')
        ->once()
        ->with($tagTeam, $datetime)
        ->andReturn($tagTeam);

    ReleaseAction::run($tagTeam, $datetime);

    Event::assertDispatched(TagTeamReleased::class);
});

test('invoke throws an exception for releasing a non releasable tag team', function ($factoryState) {
    $this->withoutExceptionHandling();

    $tagTeam = TagTeam::factory()->{$factoryState}()->create();
    $datetime = now();

    ReleaseAction::run($tagTeam, $datetime);
})->throws(CannotBeReleasedException::class)->with([
    'unemployed',
    'withFutureEmployment',
    'released',
    'retired',
]);
