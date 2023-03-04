<?php

use App\Models\TagTeam;
use App\Policies\TagTeamPolicy;

test('it authorizes a user can create a tag team', function () {
    expect((new TagTeamPolicy())->create(administrator()))->toBeTruthy();
    expect((new TagTeamPolicy())->create(basicUser()))->toBeFalsy();
});

test('it authorizes a user can update a tag team', function () {
    expect((new TagTeamPolicy())->update(administrator()))->toBeTruthy();
    expect((new TagTeamPolicy())->update(basicUser()))->toBeFalsy();
});

test('it authorizes a user can delete a tag team', function () {
    expect((new TagTeamPolicy())->delete(administrator()))->toBeTruthy();
    expect((new TagTeamPolicy())->delete(basicUser()))->toBeFalsy();
});

test('it authorizes a user can restore a tag team', function () {
    expect((new TagTeamPolicy())->restore(administrator()))->toBeTruthy();
    expect((new TagTeamPolicy())->restore(basicUser()))->toBeFalsy();
});

test('it authorizes a user can retire a tag team', function () {
    expect((new TagTeamPolicy())->retire(administrator()))->toBeTruthy();
    expect((new TagTeamPolicy())->retire(basicUser()))->toBeFalsy();
});

test('it authorizes a user can unretire a tag team', function () {
    expect((new TagTeamPolicy())->unretire(administrator()))->toBeTruthy();
    expect((new TagTeamPolicy())->unretire(basicUser()))->toBeFalsy();
});

test('it authorizes a user can suspend a tag team', function () {
    expect((new TagTeamPolicy())->suspend(administrator()))->toBeTruthy();
    expect((new TagTeamPolicy())->suspend(basicUser()))->toBeFalsy();
});

test('it authorizes a user can reinstate a tag team', function () {
    expect((new TagTeamPolicy())->reinstate(administrator()))->toBeTruthy();
    expect((new TagTeamPolicy())->reinstate(basicUser()))->toBeFalsy();
});

test('it authorizes a user can employ of a tag team', function () {
    expect((new TagTeamPolicy())->employ(administrator()))->toBeTruthy();
    expect((new TagTeamPolicy())->employ(basicUser()))->toBeFalsy();
});

test('it authorizes a user can release of a tag team', function () {
    expect((new TagTeamPolicy())->release(administrator()))->toBeTruthy();
    expect((new TagTeamPolicy())->release(basicUser()))->toBeFalsy();
});

test('it authorizes a user can view the listing of tag teams', function () {
    expect((new TagTeamPolicy())->viewList(administrator()))->toBeTruthy();
    expect((new TagTeamPolicy())->viewList(basicUser()))->toBeFalsy();
});

test('it authorizes a user can view a tag team profile', function () {
    expect((new TagTeamPolicy())->view(administrator(), TagTeam::factory()->create()))->toBeTruthy();
    expect((new TagTeamPolicy())->view(basicUser(), TagTeam::factory()->create()))->toBeFalsy();

    $user = basicUser();
    expect((new TagTeamPolicy())->view($user, TagTeam::factory()->for($user)->create()))->toBeTruthy();
});
