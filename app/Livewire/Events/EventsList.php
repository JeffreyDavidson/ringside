<?php

declare(strict_types=1);

namespace App\Livewire\Events;

use App\Models\Event;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class EventsList extends Component
{
    /**
     * Display a listing of the resource.
     */
    public function render(): View
    {
        $query = Event::query()
            ->oldest('name');

        $events = $query->paginate();

        return view('livewire.events.events-list', [
            'events' => $events,
        ]);
    }
}
