<?php

namespace Tests\Factories;

use App\Models\Event;
use App\Models\Venue;

class EventRequestDataFactory
{
    private string $name = 'Example Event Name';
    private string $date = '2021-01-01 00:00:00';
    private int $venue_id;
    private string $preview = 'This is an event preview.';

    public function __construct()
    {
        $this->venue_id = Venue::factory()->create()->id;
    }

    public static function new(): self
    {
        return new self();
    }

    public function create(array $overrides = []): array
    {
        return array_replace([
            'name' => $this->name,
            'date' => $this->date,
            'venue_id' => $this->venue_id,
            'preview' => $this->preview,
        ], $overrides);
    }

    public function withEvent(Event $event): self
    {
        $clone = clone $this;

        $this->name = $event->name;
        $this->date = $event->date;
        $this->venue_id = $event->venue->id;
        $this->preview = $event->preview;

        return $clone;
    }
}
