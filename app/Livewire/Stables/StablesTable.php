<?php

declare(strict_types=1);

namespace App\Livewire\Stables;

use App\Builders\StableBuilder;
use App\Enums\StableStatus;
use App\Livewire\Concerns\BaseTableTrait;
use App\Livewire\Concerns\Columns\HasStatusColumn;
use App\Livewire\Concerns\Filters\HasStatusFilter;
use App\Models\Stable;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class StablesTable extends DataTableComponent
{
    use BaseTableTrait, HasStatusColumn, HasStatusFilter;

    protected string $databaseTableName = 'stables';

    protected string $routeBasePath = 'stables';

    public function builder(): StableBuilder
    {
        return Stable::query()
            ->with('currentActivation')
            ->oldest('name')
            ->when($this->getAppliedFilterWithValue('Status'), fn ($query, $status) => $query->where('status', $status));
    }

    public function configure(): void {}

    public function columns(): array
    {
        return [
            Column::make(__('stables.name'), 'name')
                ->sortable()
                ->searchable(),
            $this->getDefaultStatusColumn(),
            Column::make(__('activations.start_date'), 'start_date')
                ->label(fn ($row, Column $column) => $row->currentActivation?->started_at->format('Y-m-d') ?? 'TBD'),
        ];
    }

    public function filters(): array
    {
        $statuses = collect(StableStatus::cases())->pluck('name', 'value')->toArray();

        return [
            $this->getDefaultStatusFilter($statuses),
        ];
    }
}
