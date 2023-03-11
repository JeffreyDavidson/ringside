<?php

use App\Actions\TagTeams\RemoveTagTeamPartnerAction;
use App\Events\TagTeams\TagTeamDeleted;
use App\Events\TagTeams\TagTeamEmployed;
use App\Events\TagTeams\TagTeamReinstated;
use App\Events\TagTeams\TagTeamReleased;
use App\Events\TagTeams\TagTeamRetired;
use App\Events\TagTeams\TagTeamSuspended;
use App\Events\TagTeams\TagTeamUnretired;
use App\Listeners\TagTeamSubscriber;
use App\Models\TagTeam;
use App\Models\Wrestler;
use Illuminate\Support\Facades\Event;

beforeEach(function () {
    Event::fake();
});

test('it is subscribed to events', function () {
    Event::assertListening(
        TagTeamDeleted::class,
        [TagTeamSubscriber::class, 'removeTagTeamPartners'],
    );

    Event::assertListening(
        TagTeamEmployed::class,
        [TagTeamSubscriber::class, 'employTagTeamPartners']
    );

    Event::assertListening(
        TagTeamSuspended::class,
        [TagTeamSubscriber::class, 'suspendTagTeamPartners']
    );

    Event::assertListening(
        TagTeamReinstated::class,
        [TagTeamSubscriber::class, 'reinstateTagTeamPartners']
    );

    Event::assertListening(
        TagTeamRetired::class,
        [TagTeamSubscriber::class, 'retireTagTeamPartners']
    );

    Event::assertListening(
        TagTeamReleased::class,
        [TagTeamSubscriber::class, 'releaseTagTeamPartners']
    );

    Event::assertListening(
        TagTeamUnretired::class,
        [TagTeamSubscriber::class, 'unretireTagTeamPartners']
    );
});

test('it removes a tag teams current wrestlers when deleted', function () {
    [$wrestlerA, $wrestlerB] = Wrestler::factory()->unemployed()->count(2)->create();
    $tagTeam = TagTeam::factory()
        ->withCurrentWrestlers([$wrestlerA, $wrestlerB])
        ->unemployed()
        ->create();

    RemoveTagTeamPartnerAction::shouldRun()->twice();

    app(TagTeamSubscriber::class)->removeTagTeamPartners(new TagTeamDeleted($tagTeam));
});
