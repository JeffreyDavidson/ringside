<?php

test('tag team start date cannot be changed if employment start date has past', function () {
    $tagTeam = TagTeam::factory()->bookable()->create();

    $this->createRequest(UpdateRequest::class)
        ->withParam('tag_team', $tagTeam)
        ->validate(TagTeamRequestFactory::new()->create([
            'start_date' => Carbon::now()->toDateTImeString(),
        ]))
        ->assertFailsValidation(['start_date' => EmploymentStartDateCanBeChanged::class]);
});

test('tag team start date can be changed if employment start date is in the future', function () {
    $tagTeam = TagTeam::factory()->has(Employment::factory()->started(Carbon::parse('+2 weeks')))->create();

    $this->createRequest(UpdateRequest::class)
        ->withParam('tag_team', $tagTeam)
        ->validate(TagTeamRequestFactory::new()->create([
            'start_date' => Carbon::tomorrow()->toDateString(),
        ]))
        ->assertPassesValidation();
});
