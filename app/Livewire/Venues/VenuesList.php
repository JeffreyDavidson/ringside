<?php

declare(strict_types=1);

namespace App\Livewire\Venues;

use App\Models\Venue;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class VenuesList extends Component
{
    /**
     * @var array<int>
     */
    public array $selectedVenueIds = [];

    /**
     * @var array<int>
     */
    public array $venueIdsOnPage = [];

    /**
     * Display a listing of the resource.
     */
    public function render(): View
    {
        $query = Venue::query()
            ->oldest('name');

        $venues = $query->paginate();

        $this->venueIdsOnPage = $venues->map(fn (Venue $venue) => (string) $venue->id)->toArray();

        return view('livewire.venues.venues-list', [
            'venues' => $venues,
        ]);
    }
}
