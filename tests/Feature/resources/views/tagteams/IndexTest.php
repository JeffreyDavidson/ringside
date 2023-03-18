<?php

use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;

uses(InteractsWithViews::class);

test('it contains the tag teams list component', function () {
    $this->actingAs(administrator())
        ->view('tagteams.index')
        ->assertSeeLivewire('tagteams.tagteams-list');
});
