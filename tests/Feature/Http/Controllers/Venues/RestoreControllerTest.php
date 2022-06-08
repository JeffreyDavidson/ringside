<?php

namespace Tests\Feature\Http\Controllers\Venues;

use App\Http\Controllers\Venues\RestoreController;
use App\Http\Controllers\Venues\VenuesController;
use App\Models\Venue;

beforeEach(function () {
    $this->venue = Venue::factory()->trashed()->create();
});

test('invoke restores a deleted venue and redirects', function () {
    $this->actingAs(administrator())
        ->patch(action([RestoreController::class], $this->venue))
        ->assertRedirect(action([VenuesController::class, 'index']));

    $this->assertNull($this->venue->fresh()->deleted_at);
});

test('a basic user cannot restore a venue', function () {
    $this->actingAs(basicUser())
        ->patch(action([RestoreController::class], $this->venue))
        ->assertForbidden();
});

test('a guest cannot restore a venue', function () {
    $this->patch(action([RestoreController::class], $this->venue))
        ->assertRedirect(route('login'));
});
