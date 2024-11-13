<?php

declare(strict_types=1);

namespace App\Livewire\Referees;

use App\Builders\RefereeBuilder;
use App\Enums\RefereeStatus;
use App\Livewire\Concerns\BaseTableTrait;
use App\Models\Referee;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class RefereesTable extends DataTableComponent
{
    use BaseTableTrait;

    protected string $databaseTableName = 'referees';

    protected string $routeBasePath = 'referees';

    public function builder(): RefereeBuilder
    {
        return Referee::query()
            ->with('currentEmployment')
            ->oldest('last_name')
            ->when($this->getAppliedFilterWithValue('Status'), fn ($query, $status) => $query->where('status', $status));
    }

    public function configure(): void {}

    public function columns(): array
    {
        return [
            Column::make(__('referees.name'), 'name'),
            Column::make(__('referees.status'), 'status')
                ->view('components.tables.columns.status-column'),
            Column::make(__('employments.start_date'), 'start_date')
                ->label(fn ($row, Column $column) => $row->currentEmployment?->started_at->format('Y-m-d') ?? 'TBD'),
        ];
    }

    public function filters(): array
    {
        $statuses = collect(RefereeStatus::cases())->pluck('name', 'value')->toArray();

        return [
            SelectFilter::make('Status', 'status')
                ->options(['' => 'All'] + $statuses)
                ->filter(function (Builder $builder, string $value) {
                    $builder->where('status', $value);
                }),
        ];
    }
}
