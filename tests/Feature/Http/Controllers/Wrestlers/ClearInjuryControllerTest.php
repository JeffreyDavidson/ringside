<?php

use App\Enums\WrestlerStatus;
use App\Exceptions\CannotBeClearedFromInjuryException;
use App\Http\Controllers\Wrestlers\ClearInjuryController;
use App\Http\Controllers\Wrestlers\WrestlersController;
use App\Models\TagTeam;
use App\Models\Wrestler;

test('invoke marks an injured wrestler as being cleared from injury and redirects', function () {
    $wrestler = Wrestler::factory()->injured()->create();

    $this->actingAs(administrator())
        ->patch(action([ClearInjuryController::class], $wrestler))
        ->assertRedirect(action([WrestlersController::class, 'index']));

    tap($wrestler->fresh(), function ($wrestler) {
        $this->assertNotNull($wrestler->injuries->last()->ended_at);
        $this->assertEquals(WrestlerStatus::BOOKABLE, $wrestler->status);
    });
});

test('clearing an injured wrestler on an unbookable tag team makes tag team bookable', function () {
    $injuredWrestler = Wrestler::factory()->injured()->create();
    $tagTeam = TagTeam::factory()
        ->hasAttached($injuredWrestler, ['joined_at' => now()->toDateTimeString()])
        ->hasAttached(Wrestler::factory()->bookable(), ['joined_at' => now()->toDateTimeString()])
        ->bookable()
        ->create();

    $this->actingAs(administrator())
        ->patch(action([ClearInjuryController::class], $injuredWrestler));

    tap($injuredWrestler->fresh(), function ($wrestler) {
        $this->assertTrue($wrestler->isBookable());
    });

    tap($tagTeam->fresh(), function ($tagTeam) {
        $this->assertTrue($tagTeam->isBookable());
    });
});

test('a basic user cannot mark an injured wrestler as cleared', function () {
    $wrestler = Wrestler::factory()->injured()->create();

    $this->actingAs(basicUser())
        ->patch(action([ClearInjuryController::class], $wrestler))
        ->assertForbidden();
});

test('a guest cannot mark an injured wrestler as cleared', function () {
    $wrestler = Wrestler::factory()->injured()->create();

    $this->patch(action([ClearInjuryController::class], $wrestler))
        ->assertRedirect(route('login'));
});

test('invoke throws exception for injuring a non injurable wrestler', function ($factoryState) {
    $wrestler = Wrestler::factory()->{$factoryState}()->create();

    $this->actingAs(administrator())
        ->patch(action([ClearInjuryController::class], $wrestler));
})->throws(CannotBeClearedFromInjuryException::class)->with([
    'unemployed',
    'released',
    'withFutureEmployment',
    'bookable',
    'retired',
    'suspended',
]);
