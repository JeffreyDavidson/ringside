<?php

namespace Tests\Feature\Http\Controllers\Venues;

use App\Http\Controllers\Venues\RestoreController;
use App\Http\Controllers\Venues\VenuesController;
use App\Models\Venue;

test('invoke restores a deleted venue and redirects', function () {
    $venue = Venue::factory()->trashed()->create();

    $this->actingAs(administrator())
        ->patch(action([RestoreController::class], $venue))
        ->assertRedirect(action([VenuesController::class, 'index']));

    $this->assertNull($venue->fresh()->deleted_at);
});

test('a basic user cannot restore a venue', function () {
    $venue = Venue::factory()->trashed()->create();

    $this->actingAs(basicUser())
        ->patch(action([RestoreController::class], $venue))
        ->assertForbidden();
});

test('a guest cannot restore a venue', function () {
    $venue = Venue::factory()->trashed()->create();

    $this->patch(action([RestoreController::class], $venue))
        ->assertRedirect(route('login'));
});
