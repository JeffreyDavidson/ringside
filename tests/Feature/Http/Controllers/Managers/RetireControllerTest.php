<?php

use App\Enums\ManagerStatus;
use App\Exceptions\CannotBeRetiredException;
use App\Http\Controllers\Managers\ManagersController;
use App\Http\Controllers\Managers\RetireController;
use App\Models\Manager;
use App\Models\TagTeam;
use App\Models\Wrestler;

test('invoke retires a available manager and redirects', function () {
    $manager = Manager::factory()->available()->create();

    $this->actingAs(administrator())
        ->patch(action([RetireController::class], $manager))
        ->assertRedirect(action([ManagersController::class, 'index']));

    tap($manager->fresh(), function ($manager) {
        $this->assertCount(1, $manager->retirements);
        $this->assertEquals(ManagerStatus::RETIRED, $manager->status);
    });
});

test('invoke retires an injured manager and redirects', function () {
    $manager = Manager::factory()->injured()->create();

    $this->actingAs(administrator())
        ->patch(action([RetireController::class], $manager))
        ->assertRedirect(action([ManagersController::class, 'index']));

    tap($manager->fresh(), function ($manager) {
        $this->assertCount(1, $manager->retirements);
        $this->assertEquals(ManagerStatus::RETIRED, $manager->status);
    });
});

test('invoke retires a suspended manager and redirects', function () {
    $manager = Manager::factory()->suspended()->create();

    $this->actingAs(administrator())
        ->patch(action([RetireController::class], $manager))
        ->assertRedirect(action([ManagersController::class, 'index']));

    tap($manager->fresh(), function ($manager) {
        $this->assertCount(1, $manager->retirements);
        $this->assertEquals(ManagerStatus::RETIRED, $manager->status);
    });
});

test('invoke retires a manager leaving their current tag teams and managers and redirects', function () {
    $tagTeam = TagTeam::factory()->bookable()->create();
    $wrestler = Wrestler::factory()->bookable()->create();

    $manager = Manager::factory()
        ->available()
        ->hasAttached($tagTeam, ['hired_at' => now()->toDateTimeString()])
        ->hasAttached($wrestler, ['hired_at' => now()->toDateTimeString()])
        ->create();

    $this->actingAs(administrator())
        ->patch(action([RetireController::class], $manager))
        ->assertRedirect(action([ManagersController::class, 'index']));

    expect($manager->fresh())
        ->tagTeams()->where('manageable_id', $tagTeam->id)->get()->last()->pivot->left_at->not->toBeNull()
        ->wrestlers()->where('manageable_id', $wrestler->id)->get()->last()->pivot->left_at->not->toBeNull();
});

test('a basic user cannot retire a available manager', function () {
    $manager = Manager::factory()->available()->create();

    $this->actingAs(basicUser())
        ->patch(action([RetireController::class], $manager))
        ->assertForbidden();
});

test('a guest cannot suspend a available manager', function () {
    $manager = Manager::factory()->available()->create();

    $this->patch(action([RetireController::class], $manager))
        ->assertRedirect(route('login'));
});

test('invoke throws exception for retiring a non retirable manager', function ($factoryState) {
    $this->withoutExceptionHandling();

    $manager = Manager::factory()->{$factoryState}()->create();

    $this->actingAs(administrator())
        ->patch(action([RetireController::class], $manager));
})->throws(CannotBeRetiredException::class)->with([
    'retired',
    'withFutureEmployment',
    'released',
    'unemployed',
]);
