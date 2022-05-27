<?php

use App\Enums\ManagerStatus;
use App\Exceptions\CannotBeClearedFromInjuryException;
use App\Http\Controllers\Managers\ClearInjuryController;
use App\Http\Controllers\Managers\ManagersController;
use App\Models\Manager;

test('invoke marks an injured manager as being cleared from injury and redirects', function () {
    $manager = Manager::factory()->injured()->create();

    $this->actingAs(administrator())
        ->patch(action([ClearInjuryController::class], $manager))
        ->assertRedirect(action([ManagersController::class, 'index']));

    tap($manager->fresh(), function ($manager) {
        $this->assertNotNull($manager->injuries->last()->ended_at);
        $this->assertEquals(ManagerStatus::AVAILABLE, $manager->status);
    });
});

test('a basic user cannot mark an injured manager as cleared', function () {
    $manager = Manager::factory()->injured()->create();

    $this->actingAs(basicUser())
        ->patch(action([ClearInjuryController::class], $manager))
        ->assertForbidden();
});

test('a guest cannot mark an injured manager as cleared', function () {
    $manager = Manager::factory()->injured()->create();

    $this->patch(action([ClearInjuryController::class], $manager))
        ->assertRedirect(route('login'));
});

test('invoke throws exception for injuring a non injurable manager', function ($factoryState) {
    $manager = Manager::factory()->{$factoryState}()->create();

    $this->actingAs(administrator())
        ->patch(action([ClearInjuryController::class], $manager));
})->throws(CannotBeClearedFromInjuryException::class)->with([
    'unemployed',
    'available',
    'withFutureEmployment',
    'suspended',
    'retired',
    'released',
]);
