<?php

declare(strict_types=1);

namespace App\Livewire\Managers;

use App\Models\Manager;
use App\Builders\ManagerBuilder;
use Illuminate\Support\Facades\DB;
use App\Livewire\Concerns\BaseTableTrait;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class ManagersTable extends DataTableComponent
{
    use BaseTableTrait;

    protected string $databaseTableName = 'managers';
    protected string $routeBasePath = 'managers';
    protected string $formModalPath = 'managers.modals.form-modal';
    protected string $deleteModalPath = 'managers.modals.delete-modal';
    protected string $baseModel = 'manager';

    public function configure(): void
    {
        $this->setConfigurableAreas([
            'before-wrapper' => 'components.managers.table-pre',
        ]);

        $this->setSearchPlaceholder('Search managers');

        $this->addAdditionalSelects([
            'managers.first_name as first_name',
            'managers.last_name as last_name',
        ]);
    }

    public function builder(): ManagerBuilder
    {
        return Manager::query()
            ->with('latestEmployment');
    }

    public function columns(): array
    {
        return [
            Column::make(__('managers.full_name'))
                ->label(fn ($row, Column $column) => ucwords($row->first_name.' '.$row->last_name))
                ->sortable()
                ->searchable(function (Builder $query, $searchTerm) {
                    $query->whereLike('first_name', "%{$searchTerm}%")
                        ->orWhereLike('last_name', "%{$searchTerm}%")
                        ->orWhereLike(DB::raw("CONCAT(first_name, ' ', last_name)"), "%{$searchTerm}%");
                }),
            Column::make(__('managers.status'), 'status')
                ->view('tables.columns.status'),
            Column::make(__('employments.start_date'), 'latestEmployment.started_at')
                    ->label(fn ($row, Column $column) => $row->latestEmployment?->started_at->format('Y-m-d') ?? 'TBD'),
        ];
    }
}
