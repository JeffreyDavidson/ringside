<?php

namespace App\Services;

use App\Models\Event;
use App\Repositories\EventMatchRepository;

class EventMatchService
{
    /**
     * The repository implementation.
     *
     * @var \App\Repositories\EventRepository
     */
    protected $eventRepository;

    /**
     * Create a new event service instance.
     *
     * @param \App\Repositories\EventMatchRepository $eventMatchRepository
     */
    public function __construct(EventMatchRepository $eventMatchRepository)
    {
        $this->eventMatchRepository = $eventMatchRepository;
    }

    /**
     * Create an event with given data.
     *
     * @param  \App\Models\Event $event
     * @param  array $data
     * @return \App\Models\Event
     */
    public function create(Event $event, array $data)
    {
        return $this->eventRepository->create($$data);
    }
}
