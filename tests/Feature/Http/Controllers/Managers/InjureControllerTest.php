<?php

use App\Enums\ManagerStatus;
use App\Exceptions\CannotBeInjuredException;
use App\Http\Controllers\Managers\InjureController;
use App\Http\Controllers\Managers\ManagersController;
use App\Models\Manager;

test('invoke injures an available manager and redirects', function () {
    $manager = Manager::factory()->available()->create();

    $this->actingAs(administrator())
        ->patch(action([InjureController::class], $manager))
        ->assertRedirect(action([ManagersController::class, 'index']));

    tap($manager->fresh(), function ($manager) {
        $this->assertCount(1, $manager->injuries);
        $this->assertEquals(ManagerStatus::INJURED, $manager->status);
    });
});

test('a basic user cannot injure an available manager', function () {
    $manager = Manager::factory()->available()->create();

    $this->actingAs(basicUser())
        ->patch(action([InjureController::class], $manager))
        ->assertForbidden();
});

test('a guest user cannot injure an available manager', function () {
    $manager = Manager::factory()->available()->create();

    $this->patch(action([InjureController::class], $manager))
        ->assertRedirect(route('login'));
});

test('invoke throws exception for injuring a non injurable manager', function ($factoryState) {
    $manager = Manager::factory()->{$factoryState}()->create();

    $this->actingAs(administrator())
        ->patch(action([InjureController::class], $manager));
})->throws(CannotBeInjuredException::class)->with([
    'unemployed',
    'suspended',
    'released',
    'withFutureEmployment',
    'retired',
    'injured',
]);
