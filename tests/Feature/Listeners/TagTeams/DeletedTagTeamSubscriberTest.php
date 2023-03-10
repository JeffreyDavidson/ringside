<?php

use App\Listeners\TagTeams\DeletedTagTeamSubscriber;

test('it removes a tag teams current wrestlers when deleted', function () {
    [$wrestlerA, $wrestlerB] = Wrestler::factory()->unemployed()->count(2)->create();
    $tagTeam = TagTeam::factory()
        ->hasAttached($wrestlerA, ['joined_at' => now()->toDateTimeString()])
        ->hasAttached($wrestlerB, ['joined_at' => now()->toDateTimeString()])
        ->unemployed()
        ->create();

    app(DeletedTagTeamSubscriber::class)->handleTagTeamDeleted();
});
