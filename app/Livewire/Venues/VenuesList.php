<?php

declare(strict_types=1);

namespace App\Livewire\Venues;

use App\Models\Venue;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class VenuesList extends Component
{
    /**
     * Display a listing of the resource.
     */
    public function render(): View
    {
        $query = Venue::query()
            ->oldest('name');

        $venues = $query->paginate();

        return view('livewire.venues.venues-list', [
            'venues' => $venues,
        ]);
    }
}
