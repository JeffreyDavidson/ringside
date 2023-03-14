<?php

use App\Actions\TagTeams\EmployAction;
use App\Actions\TagTeams\RemoveTagTeamPartnerAction;
use App\Actions\Wrestlers\EmployAction as WrestlersEmployAction;
use App\Actions\Wrestlers\ReinstateAction;
use App\Actions\Wrestlers\ReleaseAction;
use App\Actions\Wrestlers\RetireAction;
use App\Actions\Wrestlers\SuspendAction;
use App\Actions\Wrestlers\UnretireAction;
use App\Events\TagTeams\TagTeamDeleted;
use App\Events\TagTeams\TagTeamEmployed;
use App\Events\TagTeams\TagTeamReinstated;
use App\Events\TagTeams\TagTeamReleased;
use App\Events\TagTeams\TagTeamRetired;
use App\Events\TagTeams\TagTeamSuspended;
use App\Events\TagTeams\TagTeamUnretired;
use App\Listeners\TagTeamSubscriber;
use App\Models\TagTeam;
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
    $tagTeam = TagTeam::factory()->unemployed()->create();

    RemoveTagTeamPartnerAction::shouldRun()->twice();

    app(TagTeamSubscriber::class)->removeTagTeamPartners(new TagTeamDeleted($tagTeam));
});

test('it suspends a tag teams current wrestlers when tag team is suspended', function () {
    $tagTeam = TagTeam::factory()->bookable()->create();

    SuspendAction::shouldRun()->twice();

    app(TagTeamSubscriber::class)->suspendTagTeamPartners(new TagTeamSuspended($tagTeam, now()));
});

test('it unretires a tag teams current wrestlers when tag team is retired', function () {
    $tagTeam = TagTeam::factory()->retired()->create();

    UnretireAction::shouldRun()->twice();

    EmployAction::shouldRun()->with($tagTeam, now())->once();

    app(TagTeamSubscriber::class)->unretireTagTeamPartners(new TagTeamUnretired($tagTeam, now()));
});

test('it retires a tag teams current wrestlers when tag team is retired', function () {
    $tagTeam = TagTeam::factory()->bookable()->create();

    RetireAction::shouldRun()->twice();

    app(TagTeamSubscriber::class)->retireTagTeamPartners(new TagTeamRetired($tagTeam, now()));
});

test('it releases a tag teams current wrestlers when tag team is released', function () {
    $tagTeam = TagTeam::factory()->bookable()->create();

    ReleaseAction::shouldRun()->twice();

    app(TagTeamSubscriber::class)->releaseTagTeamPartners(new TagTeamReleased($tagTeam, now()));
});

test('it reinstates a tag teams current wrestlers when tag team is reinstated', function () {
    $tagTeam = TagTeam::factory()->suspended()->create();

    ReinstateAction::shouldRun()->twice();

    app(TagTeamSubscriber::class)->reinstateTagTeamPartners(new TagTeamReinstated($tagTeam, now()));
});

test('it employs a tag teams current wrestlers when tag team is employed', function () {
    $tagTeam = TagTeam::factory()->suspended()->create();

    WrestlersEmployAction::shouldRun()->twice();

    app(TagTeamSubscriber::class)->employTagTeamPartners(new TagTeamEmployed($tagTeam, now()));
});
