<?php

use App\Actions\Wrestlers\ClearInjuryAction;
use App\Models\Wrestler;

test('invoke marks an injured wrestler as being cleared from injury and redirects', function () {
    $wrestler = Wrestler::factory()->create();

    ClearInjuryAction::run($wrestler);

    expect($wrestler->fresh())->isInjured()->toBeFalse();
});
