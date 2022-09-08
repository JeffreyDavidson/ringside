<?php

test('deletes a wrestler and redirects', function () {
    $wrestler = Wrestler::factory()->create();

    $this->actingAs(administrator())
        ->delete(action([WrestlersController::class, 'destroy'], $wrestler))
        ->assertRedirect(action([WrestlersController::class, 'index']));

    $this->assertSoftDeleted($wrestler);
});
