<?php

use App\Data\TagTeamData;
use App\Models\Employment;
use App\Models\TagTeam;
use App\Repositories\TagTeamRepository;

test('creates a tag team without a signature move', function () {
    $data = new TagTeamData('Example Tag Team Name', null, null, null, null);

    $tagTeam = app(TagTeamRepository::class)->create($data);

    expect($tagTeam)
        ->name->toEqual('Example Tag Team Name')
        ->signature_move->toBeNull();
});

test('creates a tag team with a signature move', function () {
    $data = new TagTeamData('Example TagTeam Name', 'Tag Team Powerbomb', null, null, null);

    $tagTeam = app(TagTeamRepository::class)->create($data);

    expect($tagTeam)
        ->name->toEqual('Example TagTeam Name')
        ->signature_move->toEqual('Tag Team Powerbomb');
});

test('updates a tag team without a signature move', function () {
    $tagTeam = TagTeam::factory()->create();
    $data = new TagTeamData('Example Tag Team Name', null, null, null, null);

    $tagTeam = app(TagTeamRepository::class)->update($tagTeam, $data);

    expect($tagTeam)
        ->name->toEqual('Example Tag Team Name')
        ->signature_move->toBeNull();
});

test('updates a tag team with a signature move', function () {
    $tagTeam = TagTeam::factory()->create();
    $data = new TagTeamData('Example Tag Team Name', 'Tag Team Powerbomb', null, null, null);

    $tagTeam = app(TagTeamRepository::class)->update($tagTeam, $data);

    expect($tagTeam)
        ->name->toEqual('Example Tag Team Name')
        ->signature_move->toEqual('Tag Team Powerbomb');
});

test('deletes a tag team', function () {
    $tagTeam = TagTeam::factory()->create();

    app(TagTeamRepository::class)->delete($tagTeam);

    expect($tagTeam->fresh())
        ->deleted_at->not->toBeNull();
});

test('restores a tag team', function () {
    $tagTeam = TagTeam::factory()->trashed()->create();

    app(TagTeamRepository::class)->restore($tagTeam);

    expect($tagTeam->fresh())
        ->deleted_at->toBeNull();
});

test('employ a tag team', function () {
    $tagTeam = TagTeam::factory()->create();
    $datetime = now();

    $tagTeam = app(TagTeamRepository::class)->employ($tagTeam, $datetime);

    expect($tagTeam->fresh())->employments->toHaveCount(1);
    expect($tagTeam->fresh()->employments->first())->started_at->toEqual($datetime->toDateTimeString());
});

// test('updates employment of a tagTeam', function () {
//     $datetime = now();
//     $tagTeam = TagTeam::factory()
//         ->has(Employment::factory()->started($datetime->copy()->addDays(2)))
//         ->create();

//     expect($tagTeam->fresh())->employments->toHaveCount(1);
//     expect($tagTeam->fresh()->employments->first())
//         ->started_at->toDateTimeString()->toEqual($datetime->copy()->addDays(2)->toDateTimeString());

//     $tagTeam = app(TagTeamRepository::class)->employ($tagTeam, $datetime);

//     expect($tagTeam->fresh())->employments->toHaveCount(1);
//     expect($tagTeam->fresh()->employments->first())
//         ->started_at->toDateTimeString()->toEqual($datetime->toDateTimeString());
// });

// test('release a tagTeam', function () {
//     $tagTeam = TagTeam::factory()->bookable()->create();
//     $datetime = now();

//     $tagTeam = app(TagTeamRepository::class)->release($tagTeam, $datetime);

//     expect($tagTeam->fresh())->employments->toHaveCount(1);
//     expect($tagTeam->fresh()->employments->first())
//         ->ended_at->toDateTimeString()->toEqual($datetime->toDateTimeString());
// });

// test('injure a tagTeam', function () {
//     $tagTeam = TagTeam::factory()->bookable()->create();
//     $datetime = now();

//     $tagTeam = app(TagTeamRepository::class)->injure($tagTeam, $datetime);

//     expect($tagTeam->fresh())->injuries->toHaveCount(1);
//     expect($tagTeam->fresh()->injuries->first())
//         ->started_at->toDateTimeString()->toEqual($datetime->toDateTimeString());
// });

// test('clear an injured tagTeam', function () {
//     $tagTeam = TagTeam::factory()->injured()->create();
//     $datetime = now();

//     $tagTeam = app(TagTeamRepository::class)->clearInjury($tagTeam, $datetime);

//     expect($tagTeam->fresh())->injuries->toHaveCount(1);
//     expect($tagTeam->fresh()->injuries->first())
//         ->ended_at->toDateTimeString()->toEqual($datetime->toDateTimeString());
// });

// test('retire a tagTeam', function () {
//     $tagTeam = TagTeam::factory()->bookable()->create();
//     $datetime = now();

//     $tagTeam = app(TagTeamRepository::class)->retire($tagTeam, $datetime);

//     expect($tagTeam->fresh())->retirements->toHaveCount(1);
//     expect($tagTeam->fresh()->retirements->first())
//         ->started_at->toDateTimeString()->toEqual($datetime->toDateTimeString());
// });

// test('unretire a tagTeam', function () {
//     $tagTeam = TagTeam::factory()->retired()->create();
//     $datetime = now();

//     $tagTeam = app(TagTeamRepository::class)->unretire($tagTeam, $datetime);

//     expect($tagTeam->fresh())->retirements->toHaveCount(1);
//     expect($tagTeam->fresh()->retirements->first())
//         ->ended_at->toDateTimeString()->toEqual($datetime->toDateTimeString());
// });

// test('suspend a tagTeam', function () {
//     $tagTeam = TagTeam::factory()->bookable()->create();
//     $datetime = now();

//     $tagTeam = app(TagTeamRepository::class)->suspend($tagTeam, $datetime);

//     expect($tagTeam->fresh())->suspensions->toHaveCount(1);
//     expect($tagTeam->fresh()->suspensions->first())
//         ->started_at->toDateTimeString()->toEqual($datetime->toDateTimeString());
// });

// test('reinstate a tagTeam', function () {
//     $tagTeam = TagTeam::factory()->suspended()->create();
//     $datetime = now();

//     $tagTeam = app(TagTeamRepository::class)->reinstate($tagTeam, $datetime);

//     expect($tagTeam->fresh())->suspensions->toHaveCount(1);
//     expect($tagTeam->fresh()->suspensions->first())
//         ->ended_at->toDateTimeString()->toEqual($datetime->toDateTimeString());
// });

// test('remove a tagTeam from their current tag team', function () {
//     $tagTeam = TagTeam::factory()
//         ->bookable()
//         ->onCurrentTagTeam($tagTeam = TagTeam::factory()->bookable()->create())
//         ->create();
//     $datetime = now();

//     expect($tagTeam->fresh())->currentTagTeam->id->toBe($tagTeam->id);
//     expect($tagTeam->fresh()->tagTeams->first()->pivot)->left_at->toBeNull();

//     app(TagTeamRepository::class)->removeFromCurrentTagTeam($tagTeam, $datetime);

//     expect($tagTeam->fresh())->currentTagTeam->toBeNull();
//     expect($tagTeam->fresh()->tagTeams->first()->pivot)->left_at->not->toBeNull();
// });

// test('it can query available tagTeams that can join a new tag team', function () {
//     $bookableTagTeam = TagTeam::factory()->bookable()->create();
//     $injuredTagTeam = TagTeam::factory()->injured()->create();
//     $suspendedTagTeam = TagTeam::factory()->suspended()->create();
//     $releasedTagTeam = TagTeam::factory()->released()->create();
//     $retiredTagTeam = TagTeam::factory()->retired()->create();
//     $unemployedTagTeam = TagTeam::factory()->unemployed()->create();
//     $futureEmployedTagTeam = TagTeam::factory()->withFutureEmployment()->create();
//     $tagTeamTagTeam = TagTeam::factory()->bookable()->onCurrentTagTeam()->create();

//     $tagTeams = app(TagTeamRepository::class)->getAvailableTagTeamsForNewTagTeam();

//     expect($tagTeams)
//         ->toHaveCount(3)
//         ->collectionHas($bookableTagTeam)
//         ->collectionHas($unemployedTagTeam)
//         ->collectionHas($futureEmployedTagTeam)
//         ->collectionDoesntHave($injuredTagTeam)
//         ->collectionDoesntHave($suspendedTagTeam)
//         ->collectionDoesntHave($retiredTagTeam)
//         ->collectionDoesntHave($releasedTagTeam)
//         ->collectionDoesntHave($tagTeamTagTeam);
// });

// test('it can query available tagTeams that can join an existing tag team', function () {
//     $tagTeam = TagTeam::factory()->create();
//     $bookableTagTeam = TagTeam::factory()->bookable()->create();
//     $injuredTagTeam = TagTeam::factory()->injured()->create();
//     $suspendedTagTeam = TagTeam::factory()->suspended()->create();
//     $releasedTagTeam = TagTeam::factory()->released()->create();
//     $retiredTagTeam = TagTeam::factory()->retired()->create();
//     $unemployedTagTeam = TagTeam::factory()->unemployed()->create();
//     $futureEmployedTagTeam = TagTeam::factory()->withFutureEmployment()->create();
//     $tagTeamTagTeam = TagTeam::factory()->bookable()->onCurrentTagTeam($tagTeam)->create();

//     $tagTeams = app(TagTeamRepository::class)->getAvailableTagTeamsForExistingTagTeam($tagTeam);

//     expect($tagTeams)
//         ->toHaveCount(4)
//         ->collectionHas($bookableTagTeam)
//         ->collectionHas($unemployedTagTeam)
//         ->collectionHas($futureEmployedTagTeam)
//         ->collectionHas($tagTeamTagTeam)
//         ->collectionDoesntHave($injuredTagTeam)
//         ->collectionDoesntHave($suspendedTagTeam)
//         ->collectionDoesntHave($retiredTagTeam)
//         ->collectionDoesntHave($releasedTagTeam);
// });
