<?php

declare(strict_types=1);

namespace App\Livewire\Events;

use App\Builders\EventBuilder;
use App\Enums\EventStatus;
use App\Livewire\Concerns\BaseTableTrait;
use App\Models\Event;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class EventsTable extends DataTableComponent
{
    use BaseTableTrait;

    protected string $databaseTableName = 'events';

    protected string $routeBasePath = 'events';

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
            Column::make(__('events.name'), 'name'),
            Column::make(__('events.status'), 'status')
                ->view('components.tables.columns.status-column'),
            Column::make(__('events.date'), 'date'),
            Column::make(__('venues.name'), 'venue_name'),
        ];
    }

    public function filters(): array
    {
        $statuses = collect(EventStatus::cases())->pluck('name', 'value')->toArray();

        return [
            SelectFilter::make('Status', 'status')
                ->options(['' => 'All'] + $statuses)
                ->filter(function (Builder $builder, string $value) {
                    $builder->where('status', $value);
                }),
        ];
    }
}
