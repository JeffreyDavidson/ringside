<?php

use App\Actions\EventMatches\AddMatchForEventAction;
use App\Actions\EventMatches\AddRefereesToMatchAction;
use App\Data\EventMatchData;
use App\Http\Requests\EventMatches\StoreRequest;
use App\Models\Event;
use App\Models\MatchType;
use Database\Seeders\MatchTypesTableSeeder;

beforeEach(function () {
    $this->seed(MatchTypesTableSeeder::class);
    $this->event = Event::factory()->scheduled()->create();
});

test('add a match to an event', function () {
    $storeRequest = StoreRequest::factory()->create();
    $storeRequest = new StoreRequest([], $storeRequest);
    $eventMatchData = EventMatchData::fromStoreRequest($storeRequest);

    AddMatchForEventAction::run($this->event, $eventMatchData);

    AddRefereesToMatchAction::shouldReceive('run');
});
