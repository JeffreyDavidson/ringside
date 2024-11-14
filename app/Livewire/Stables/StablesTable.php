<?php

declare(strict_types=1);

namespace App\Livewire\Stables;

use App\Builders\StableBuilder;
use App\Enums\StableStatus;
use App\Livewire\Concerns\BaseTableTrait;
use App\Livewire\Concerns\Columns\HasActivationDateColumn;
use App\Livewire\Concerns\Columns\HasStatusColumn;
use App\Livewire\Concerns\Filters\HasActivationDateFilter;
use App\Livewire\Concerns\Filters\HasStatusFilter;
use App\Models\Stable;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class StablesTable extends DataTableComponent
{
    use BaseTableTrait, HasActivationDateColumn, HasActivationDateFilter, HasStatusColumn, HasStatusFilter;

    protected string $databaseTableName = 'stables';

    protected string $routeBasePath = 'stables';

    protected string $resourceName = 'stables';

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
            $this->getDefaultActivationDateColumn(),
        ];
    }

    public function filters(): array
    {
        $statuses = collect(StableStatus::cases())->pluck('name', 'value')->toArray();

        return [
            $this->getDefaultStatusFilter($statuses),
            $this->getDefaultActivationmDateFilter(),
        ];
    }
}
