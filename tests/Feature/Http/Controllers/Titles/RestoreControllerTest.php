<?php

namespace Tests\Feature\Http\Controllers\Titles;

use App\Http\Controllers\Titles\RestoreController;
use App\Http\Controllers\Titles\TitlesController;
use App\Models\Title;

test('invoke restores a deleted title and redirects', function () {
    $title = Title::factory()->trashed()->create();

    $this->actingAs(administrator())
        ->patch(action([RestoreController::class], $title))
        ->assertRedirect(action([TitlesController::class, 'index']));

    $this->assertNull($this->title->fresh()->deleted_at);
});

test('a basic user cannot restore a title', function () {
    $title = Title::factory()->trashed()->create();

    $this->actingAs(basicUser())
        ->patch(action([RestoreController::class], $title))
        ->assertForbidden();
});

test('a guest cannot restore a title', function () {
    $title = Title::factory()->trashed()->create();

    $this->patch(action([RestoreController::class], $title))
        ->assertRedirect(route('login'));
});
