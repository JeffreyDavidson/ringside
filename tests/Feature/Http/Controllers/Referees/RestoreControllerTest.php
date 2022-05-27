<?php

use App\Http\Controllers\Referees\RefereesController;
use App\Http\Controllers\Referees\RestoreController;
use App\Models\Referee;

test('invoke restores a deleted referee and redirects', function () {
    $referee = Referee::factory()->trashed()->create();

    $this->actingAs(administrator())
        ->patch(action([RestoreController::class], $referee))
        ->assertRedirect(action([RefereesController::class, 'index']));

    $this->assertNull($referee->fresh()->deleted_at);
});

test('a basic user cannot restore a deleted referee', function () {
    $referee = Referee::factory()->trashed()->create();

    $this->actingAs(basicUser())
        ->patch(action([RestoreController::class], $referee))
        ->assertForbidden();
});

test('a guest cannot restore a deleted referee', function () {
    $referee = Referee::factory()->trashed()->create();

    $this->patch(action([RestoreController::class], $referee))
        ->assertRedirect(route('login'));
});
