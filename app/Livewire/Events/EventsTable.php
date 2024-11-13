<?php

declare(strict_types=1);

namespace App\Livewire\Events;

use App\Builders\EventBuilder;
use App\Livewire\Concerns\BaseTableTrait;
use App\Models\Event;
use Illuminate\Contracts\View\View;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class EventsTable extends DataTableComponent
{
    use BaseTableTrait;

    protected string $databaseTableName = 'events';

    protected string $routeBasePath = 'events';

    public function builder(): EventBuilder
    {
        return Event::query()
            ->oldest('name');
    }

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
}
