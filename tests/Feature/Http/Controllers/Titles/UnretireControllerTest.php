<?php

use App\Enums\TitleStatus;
use App\Exceptions\CannotBeUnretiredException;
use App\Http\Controllers\Titles\TitlesController;
use App\Http\Controllers\Titles\UnretireController;
use App\Models\Title;

test('invoke unretires a retired title and redirects', function () {
    $title = Title::factory()->retired()->create();

    $this->actingAs(administrator())
        ->patch(action([UnretireController::class], $title))
        ->assertRedirect(action([TitlesController::class, 'index']));

    tap($title->fresh(), function ($title) {
        $this->assertNotNull($title->retirements->last()->ended_at);
        $this->assertEquals(TitleStatus::ACTIVE, $title->status);
    });
});

test('a basic user cannot unretire a title', function () {
    $title = Title::factory()->retired()->create();

    $this->actingAs(basicUser())
        ->patch(action([UnretireController::class], $title))
        ->assertForbidden();
});

test('a guest cannot unretire a title', function () {
    $title = Title::factory()->retired()->create();

    $this->patch(action([UnretireController::class], $title))
        ->assertRedirect(route('login'));
});

test('invoke throws exception for unretiring a non unretirable title', function ($factoryState) {
    $this->withoutExceptionHandling();

    $title = Title::factory()->{$factoryState}()->create();

    $this->actingAs(administrator())
        ->patch(action([UnretireController::class], $title));
})->throws(CannotBeUnretiredException::class)->with([
    'active',
    'inactive',
    'withFutureActivation',
    'unactivated',
]);
