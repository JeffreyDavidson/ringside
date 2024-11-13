<?php

declare(strict_types=1);

namespace App\Livewire\Events;

use App\Models\Event;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class EventsList extends Component
{
    /**
     * @var array<int>
     */
    public array $selectedEventIds = [];

    /**
     * @var array<int>
     */
    public array $eventIdsOnPage = [];

    /**
     * Display a listing of the resource.
     */
    public function render(): View
    {
        $query = Event::query()
            ->oldest('name');

        $events = $query->paginate();

        $this->eventIdsOnPage = $events->map(fn (Event $event) => (string) $event->id)->toArray();

        return view('livewire.events.events-list', [
            'events' => $events,
        ]);
    }
}
