<?php

use App\Enums\TitleStatus;
use App\Exceptions\CannotBeActivatedException;
use App\Http\Controllers\Titles\ActivateController;
use App\Http\Controllers\Titles\TitlesController;
use App\Models\Title;

test('invoke activates an unactivated title and redirects', function () {
    $title = Title::factory()->unactivated()->create();

    $this->actingAs(administrator())
        ->patch(action([ActivateController::class], $title))
        ->assertRedirect(action([TitlesController::class, 'index']));

    tap($title->fresh(), function ($title) {
        $this->assertCount(1, $title->activations);
        $this->assertEquals(TitleStatus::ACTIVE, $title->status);
    });
});

test('invoke activates a future activated title and redirects', function () {
    $title = Title::factory()->withFutureActivation()->create();

    $this->actingAs(administrator())
        ->patch(action([ActivateController::class], $title))
        ->assertRedirect(action([TitlesController::class, 'index']));

    tap($title->fresh(), function ($title) {
        $this->assertCount(1, $title->activations);
        $this->assertEquals(TitleStatus::ACTIVE, $title->status);
    });
});

test('a basic user cannot activate an unactivated title', function () {
    $title = Title::factory()->unactivated()->create();

    $this->actingAs(basicUser())
        ->patch(action([ActivateController::class], $title))
        ->assertForbidden();
});

test('a guest cannot unretire a title', function () {
    $title = Title::factory()->retired()->create();

    $this->patch(action([ActivateController::class], $title))
        ->assertRedirect(route('login'));
});

test('invoke throws exception for unretiring a non unretirable title', function ($factoryState) {
    $this->withoutExceptionHandling();

    $title = Title::factory()->{$factoryState}()->create();

    $this->actingAs(administrator())
        ->patch(action([ActivateController::class], $title));
})->throws(CannotBeActivatedException::class)->with([
    'active',
    'retired',
]);
