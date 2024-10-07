<?php

declare(strict_types=1);

namespace App\Livewire\Wrestlers;

use App\Livewire\Concerns\ExtraTableTrait;
use App\Models\EventMatch;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class WrestlerMatchesTable extends DataTableComponent
{
    use ExtraTableTrait;

    /**
     * WrestlerId to use for component.
     */
    public int $wrestlerId;

    public function configure(): void
    {
    }

    public function builder(): Builder
    {
        return EventMatch::query();
    }

    public function columns(): array
    {
        return [
            Column::make(__('events.name'))
                ->sortable()
                ->searchable(),
            Column::make(__('events.date'))
                ->sortable()
                ->searchable(),
            Column::make(__('matches.opponents'))
                ->sortable()
                ->searchable(),
            Column::make(__('matches.titles'))
                ->sortable()
                ->searchable(),
            Column::make(__('matches.result'))
                ->sortable()
                ->searchable(),
        ];
    }
}
