<?php

declare(strict_types=1);

namespace App\Livewire\Venues;

use App\Models\Venue;
use Illuminate\Contracts\View\View;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class PreviousEventsTable extends DataTableComponent
{
    public Venue $venue;

    public function mount(Venue $venue): void
    {
        $this->venue = $venue;
    }

    public function configure(): void {}

    public function columns(): array
    {
        return [
            Column::make(__('events.name'), 'name'),
            Column::make(__('events.date'), 'date'),
        ];
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
