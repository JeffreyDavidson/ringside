<?php

declare(strict_types=1);

namespace App\Livewire\Venues;

use App\Models\Venue;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class PreviousEventsList extends Component
{
    public Venue $venue;

    public function mount(Venue $venue): void
    {
        $this->venue = $venue;
    }

    /**
     * Display a listing of the resource.
     */
    public function render(): View
    {
        $query = $this->venue
            ->previousEvents()
            ->oldest('name');

        $previousEvents = $query->paginate();

        return view('livewire.venues.previous-events.previous-events-list', [
            'previousEvents' => $previousEvents,
        ]);
    }
}
