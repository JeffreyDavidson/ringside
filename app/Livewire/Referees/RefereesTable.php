<?php

declare(strict_types=1);

namespace App\Livewire\Referees;

use App\Builders\RefereeBuilder;
use App\Livewire\Concerns\BaseTableTrait;
use App\Models\Referee;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class RefereesTable extends DataTableComponent
{
    use BaseTableTrait;

    protected string $databaseTableName = 'referees';

    protected string $routeBasePath = 'referees';

    protected string $formModalPath = 'referees.modals.form-modal';

    protected string $deleteModalPath = 'referees.modals.delete-modal';

    protected string $baseModel = 'referee';

    public function configure(): void
    {
        $this->setConfigurableAreas([
            'before-wrapper' => 'components.referees.table-pre',
        ]);

        $this->setSearchPlaceholder('Search referees');

        $this->addAdditionalSelects([
            'referees.first_name as first_name',
            'referees.last_name as last_name',
        ]);
    }

    public function builder(): RefereeBuilder
    {
        return Referee::query()
            ->with('latestEmployment');
    }

    public function columns(): array
    {
        return [
            Column::make(__('referees.full_name'))
                ->label(fn ($row, Column $column) => ucwords($row->first_name.' '.$row->last_name))
                ->sortable()
                ->searchable(function (Builder $query, $searchTerm) {
                    $query->whereLike('first_name', "%{$searchTerm}%")
                        ->orWhereLike('last_name', "%{$searchTerm}%")
                        ->orWhereLike(DB::raw("CONCAT(first_name, ' ', last_name)"), "%{$searchTerm}%");
                }),
            Column::make(__('referees.status'), 'status')
                ->view('tables.columns.status'),
            Column::make(__('employments.start_date'), 'latestEmployment.started_at')
                ->label(fn ($row, Column $column) => $row->latestEmployment?->started_at->format('Y-m-d') ?? 'TBD'),
        ];
    }
}
