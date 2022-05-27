<?php

use App\Http\Controllers\Wrestlers\RestoreController;
use App\Http\Controllers\Wrestlers\WrestlersController;
use App\Models\Wrestler;

test('invoke restores a deleted wrestler and redirects', function () {
    $wrestler = Wrestler::factory()->trashed()->create();

    $this->actingAs(administrator())
        ->patch(action([RestoreController::class], $wrestler))
        ->assertRedirect(action([WrestlersController::class, 'index']));

    $this->assertNull($wrestler->fresh()->deleted_at);
});

test('a basic user cannot restore a deleted wrestler', function () {
    $wrestler = Wrestler::factory()->trashed()->create();

    $this->actingAs(basicUser())
        ->patch(action([RestoreController::class], $wrestler))
        ->assertForbidden();
});

test('a guest cannot restore a deleted wrestler', function () {
    $wrestler = Wrestler::factory()->trashed()->create();

    $this->patch(action([RestoreController::class], $wrestler))
        ->assertRedirect(route('login'));
});
