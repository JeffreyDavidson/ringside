<?php

declare(strict_types=1);

namespace App\Livewire\Wrestlers;

use App\Builders\WrestlerBuilder;
use App\Enums\WrestlerStatus;
use App\Livewire\Concerns\BaseTableTrait;
use App\Livewire\Concerns\Columns\HasEmploymentDateColumn;
use App\Livewire\Concerns\Columns\HasStatusColumn;
use App\Livewire\Concerns\Filters\HasEmploymentDateFilter;
use App\Livewire\Concerns\Filters\HasStatusFilter;
use App\Models\Wrestler;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class WrestlersTable extends DataTableComponent
{
    use BaseTableTrait, HasEmploymentDateColumn, HasEmploymentDateFilter, HasStatusColumn, HasStatusFilter;

    protected string $databaseTableName = 'wrestlers';

    protected string $routeBasePath = 'wrestlers';

    public function builder(): WrestlerBuilder
    {
        return Wrestler::query()
            ->with('currentEmployment')
            ->when(
                $this->getAppliedFilterWithValue('Status'),
                fn ($query, $status) => $query->where('status', $status)
            )
            ->when(
                $this->getAppliedFilterWithValue('Employment'),
                fn ($query, $dateRange) => $query
                    ->whereDate('wrestler_employments.started_at', '>=', $dateRange['minDate'])
                    ->whereDate('wrestler_employments.ended_at', '<=', $dateRange['maxDate'])
            );

    }

    public function configure(): void {}

    public function columns(): array
    {
        return [
            Column::make(__('wrestlers.name'), 'name')
                ->sortable()
                ->searchable(),
            $this->getDefaultStatusColumn(),
            Column::make(__('wrestlers.height'), 'height'),
            Column::make(__('wrestlers.weight'), 'weight'),
            Column::make(__('wrestlers.hometown'), 'hometown'),
            $this->getDefaultEmploymentDateColumn(),
        ];
    }

    public function filters(): array
    {
        $statuses = collect(WrestlerStatus::cases())->pluck('name', 'value')->toArray();

        return [
            $this->getDefaultStatusFilter($statuses),
            $this->getDefaultEmploymentDateFilter(),
        ];
    }
}
