<?php

use App\Actions\EventMatches\AddMatchForEventAction;
use App\Data\EventMatchData;
use App\Http\Requests\EventMatches\StoreRequest;
use App\Models\Event;

test('store creates a non title singles match for an event and redirects', function () {
    $event = Event::factory()->create();
    $request = StoreRequest::factory()->create();
    dd($request);
    $data = EventMatchData::fromStoreRequest($request);

    $match = AddMatchForEventAction::run($event, $data);

    expect($this->event->fresh()->matches()->first())
        ->match_type_id->toEqual(1)
        ->titles->toBeEmpty()
        ->referees->toHaveCount(1)
        ->competitors->toHaveCount(2);

    expect($this->event->fresh()->matches()->first()->competitors)
        ->toHaveCount(2)
        ->each(fn ($competitor) => $competitor->toBeInstanceOf(Wrestler::class));
});

test('store creates a title match for an event and redirects', function () {
    $title = Title::factory()->active()->create();
    $data = StoreRequest::factory()->create([
        'titles' => [$title->id],
    ]);

    dd($this
        ->actingAs(administrator())
        ->from(action([EventMatchesController::class, 'create'], $this->event))
        ->post(action([EventMatchesController::class, 'store'], $this->event), $data));

    expect($this->event->fresh())
        ->matches->toHaveCount(1)
        ->first()
        ->titles->toHaveCount(1)->assertCollectionHas($title);
});
