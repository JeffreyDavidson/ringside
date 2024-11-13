<?php

declare(strict_types=1);

namespace App\Livewire\Venues;

use App\Models\Venue;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class VenuesList extends Component
{
    /**
     * Determines if the filters should be shown.
     */
    public bool $showFilters = false;

    /**
     * Shows list of accepted filters and direction to be displayed.
     *
     * @var array<string, string>
     */
    public array $filters = [
        'search' => '',
    ];

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
            ->when(
                $this->filters['search'],
                function (Builder $query, string $search) {
                    $query->where('name', 'like', '%'.$search.'%');
                }
            )
            ->oldest('name');

        $venues = $query->paginate();

        $this->venueIdsOnPage = $venues->map(fn (Venue $venue) => (string) $venue->id)->toArray();

        return view('livewire.venues.venues-list', [
            'venues' => $venues,
        ]);
    }
}
