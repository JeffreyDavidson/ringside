<?php

declare(strict_types=1);

namespace App\Livewire\Managers;

use App\Builders\ManagerBuilder;
use App\Enums\ManagerStatus;
use App\Livewire\Concerns\BaseTableTrait;
use App\Models\Manager;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class ManagersTable extends DataTableComponent
{
    use BaseTableTrait;

    protected string $databaseTableName = 'managers';

    protected string $routeBasePath = 'managers';

    public function builder(): ManagerBuilder
    {
        return Manager::query()
            ->oldest('last_name')
            ->when($this->getAppliedFilterWithValue('Status'), fn ($query, $status) => $query->where('status', $status));
    }

    public function configure(): void
    {
    }

    public function columns(): array
    {
        return [
            Column::make(__('managers.name'), 'name'),
            Column::make(__('managers.status'), 'status')
                ->view('components.tables.columns.status-column'),
            Column::make(__('employments.start_date'), 'start_date'),
        ];
    }

    public function filters(): array
    {
        $statuses = collect(ManagerStatus::cases())->pluck('name', 'value')->toArray();

        return [
            SelectFilter::make('Status', 'status')
                ->options(['' => 'All'] + $statuses)
                ->filter(function (Builder $builder, string $value) {
                    $builder->where('status', $value);
                }),
        ];
    }
}
