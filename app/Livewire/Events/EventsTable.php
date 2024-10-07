<?php

declare(strict_types=1);

namespace App\Livewire\Events;

use App\Builders\EventBuilder;
use App\Livewire\Concerns\BaseTableTrait;
use App\Models\Event;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class EventsTable extends DataTableComponent
{
    use BaseTableTrait;

    protected string $databaseTableName = 'events';

    protected string $routeBasePath = 'events';

    protected string $formModalPath = 'events.modals.form-modal';

    protected string $deleteModalPath = 'events.modals.delete-modal';

    protected string $baseModel = 'event';

    public function configure(): void
    {
        $this->setConfigurableAreas([
            'before-wrapper' => 'components.events.table-pre',
        ]);

        $this->setSearchPlaceholder('Search events');
    }

    public function builder(): EventBuilder
    {
        return Event::query();
    }

    public function columns(): array
    {
        return [
            Column::make(__('events.name'), 'name')
                ->sortable()
                ->searchable(),
            Column::make(__('events.status'), 'status')
                ->view('tables.columns.status'),
            Column::make(__('events.date'), 'date')
                ->label(fn ($row, Column $column) => $row->date?->format('Y-m-d') ?? 'TBD')
                ->sortable(),
        ];
    }
}
