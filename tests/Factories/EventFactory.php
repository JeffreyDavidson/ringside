<?php

namespace Tests\Factories;

use App\Enums\EventStatus;
use App\Models\Event;
use Carbon\Carbon;
use Faker\Generator;
use Tests\Factories\VenueFactory;

class EventFactory extends BaseFactory
{
    /** @var VenueFactory|null */
    public $venueFactory;
    public $softDeleted = false;

    public function scheduled()
    {
        $clone = clone $this;
        $clone->attributes['status'] = EventStatus::SCHEDULED;
        $clone->attributes['date'] = Carbon::tomorrow()->toDateTimeString();

        return $clone;
    }

    public function past()
    {
        $clone = clone $this;
        $clone->attributes['status'] = EventStatus::PAST;
        $clone->attributes['date'] = Carbon::yesterday()->toDateTimeString();

        return $clone;
    }

    public function create($attributes = [])
    {
        return $this->make(function ($attributes) {
            $event = Event::create($this->resolveAttributes($attributes));

            if ($this->softDeleted) {
                $event->delete();
            }

            return $event;
        }, $attributes);
    }

    public function atVenue(VenueFactory $venueFactory = null)
    {
        $clone = clone $this;
        $clone->attributes['venue_id'] = $venueFactory ?? VenueFactory::new();

        return $clone;
    }

    protected function defaultAttributes(Generator $faker)
    {
        return [
            'name' => $faker->words(2, true),
            'date' => $faker->dateTime(),
            'venue_id' => VenueFactory::new()->create()->id,
            'preview' => $faker->paragraph(),
        ];
    }
}
