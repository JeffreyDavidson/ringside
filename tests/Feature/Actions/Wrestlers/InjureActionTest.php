<?php

test('invoke injures a bookable wrestler and redirects', function () {
    $this->actingAs(administrator())
        ->patch(action([InjureController::class], $this->wrestler))
        ->assertRedirect(action([WrestlersController::class, 'index']));

    expect($this->wrestler->fresh())
        ->injuries->toHaveCount(1)
        ->status->toMatchObject(WrestlerStatus::INJURED);
});

test('injuring a bookable wrestler on a bookable tag team makes tag team unbookable', function () {
    $tagTeam = TagTeam::factory()->bookable()->create();
    $wrestler = $tagTeam->currentWrestlers()->first();

    $this->actingAs(administrator())
        ->patch(action([InjureController::class], $wrestler))
        ->assertRedirect(action([WrestlersController::class, 'index']));

    expect($wrestler->currentTagTeam->fresh())
        ->status->toMatchObject(TagTeamStatus::UNBOOKABLE);
});

test('invoke throws exception for injuring a non injurable wrestler', function ($factoryState) {
    $this->withoutExceptionHandling();

    $wrestler = Wrestler::factory()->{$factoryState}()->create();

    $this->actingAs(administrator())
        ->patch(action([InjureController::class], $wrestler));
})->throws(CannotBeInjuredException::class)->with([
    'unemployed',
    'suspended',
    'released',
    'withFutureEmployment',
    'retired',
    'injured',
]);
