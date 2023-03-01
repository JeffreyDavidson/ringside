<?php

use App\Actions\Wrestlers\ClearInjuryAction;
use App\Enums\WrestlerStatus;
use App\Exceptions\CannotBeClearedFromInjuryException;
use App\Models\Wrestler;
use App\Repositories\WrestlerRepository;
use Illuminate\Support\Carbon;
use function Pest\Laravel\mock;
use function Spatie\PestPluginTestTime\testTime;

test('it clears an injury of an injured wrestler at the current datetime by default', function () {
    testTime()->freeze();
    $wrestler = Wrestler::factory()->injured()->create();
    $datetime = now();

    mock(WrestlerRepository::class)
        ->shouldReceive('clearInjury')
        ->once()
        ->with($wrestler, $datetime)
        ->andReturn($wrestler);

    ClearInjuryAction::run($wrestler);
});

test('it clears an injury of an injured wrestler at a specific datetime', function () {
    testTime()->freeze();
    $wrestler = Wrestler::factory()->injured()->create();
    $datetime = now()->addDays(2);

    mock(WrestlerRepository::class)
        ->shouldReceive('clearInjury')
        ->once()
        ->with($wrestler, $datetime)
        ->andReturn($wrestler);

    ClearInjuryAction::run($wrestler, $datetime);
});

test('clearing an injured wrestler on an unbookable tag team makes tag team bookable', function () {
    $bookableWrestler = Wrestler::factory()->bookable()->create();
    $tagTeam = TagTeam::factory()
        ->hasAttached($this->wrestler, ['joined_at' => Carbon::yesterday()->toDateTimeString()])
        ->hasAttached($bookableWrestler, ['joined_at' => Carbon::yesterday()->toDateTimeString()])
        ->has(Employment::factory()->started(Carbon::yesterday()))
        ->create();

    ClearInjuryAction::run($this->wrestler);

    expect($this->wrestler->fresh())
        ->status->toMatchObject(WrestlerStatus::BOOKABLE);

    expect($tagTeam->fresh())
        ->status->toMatchObject(TagTeamStatus::BOOKABLE);
})->skip();

test('it throws exception for injuring a non injurable wrestler', function ($factoryState) {
    $this->withoutExceptionHandling();

    $wrestler = Wrestler::factory()->{$factoryState}()->create();

    ClearInjuryAction::run($wrestler);
})->throws(CannotBeClearedFromInjuryException::class)->with([
    WrestlerStatus::UNEMPLOYED,
    WrestlerStatus::RELEASED,
    WrestlerStatus::FUTURE_EMPLOYMENT,
    WrestlerStatus::BOOKABLE,
    WrestlerStatus::RETIRED,
    WrestlerStatus::SUSPENDED,
])->skip();
