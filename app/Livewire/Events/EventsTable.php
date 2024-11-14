<?php

declare(strict_types=1);

namespace App\Livewire\Events;

use App\Builders\EventBuilder;
use App\Enums\EventStatus;
use App\Livewire\Concerns\BaseTableTrait;
use App\Livewire\Concerns\Columns\HasStatusColumn;
use App\Livewire\Concerns\Filters\HasStatusFilter;
use App\Models\Event;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class EventsTable extends DataTableComponent
{
    use BaseTableTrait, HasStatusColumn, HasStatusFilter;

    protected string $databaseTableName = 'events';

    protected string $routeBasePath = 'events';

    protected string $resourceName = 'events';

    public function builder(): EventBuilder
    {
        return Event::query()
            ->oldest('name')
            ->when($this->getAppliedFilterWithValue('Status'), fn ($query, $status) => $query->where('status', $status));
    }

    public function configure(): void {}

    public function columns(): array
    {
        return [
            Column::make(__('events.name'), 'name')
                ->sortable()
                ->searchable(),
            $this->getDefaultStatusColumn(),
            Column::make(__('events.date'), 'date'),
            Column::make(__('venues.name'), 'venue_name'),
        ];
    }

    public function filters(): array
    {
        $statuses = collect(EventStatus::cases())->pluck('name', 'value')->toArray();

        return [
            $this->getDefaultStatusFilter($statuses),
        ];
    }
}
