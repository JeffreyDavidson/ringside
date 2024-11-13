<?php

declare(strict_types=1);

namespace App\Livewire\Events;

use App\Models\Event;
use Illuminate\Contracts\View\View;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class EventsList extends DataTableComponent
{
    public function configure(): void
    {
    }

    public function columns(): array
    {
        return [
            Column::make(__('events.name'), 'name'),
            Column::make(__('events.status'), 'status')
                ->view('components.tables.columns.status-column'),
            Column::make(__('events.date'), 'date'),
            Column::make(__('venues.name'), 'venue_name'),
        ];
    }

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
