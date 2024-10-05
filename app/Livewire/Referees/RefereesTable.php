<?php

declare(strict_types=1);

namespace App\Livewire\Referees;

use App\Builders\RefereeBuilder;
use App\Livewire\Concerns\BaseTableTrait;
use App\Models\Referee;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class RefereesTable extends DataTableComponent
{
    use BaseTableTrait;

    protected string $databaseTableName = 'referees';

    protected string $routeBasePath = 'referees';

    protected string $modalPath = 'referees.modals.form-modal';

    public function configure(): void
    {
        $this->setConfigurableAreas([
            'before-wrapper' => 'components.referees.table-pre',
        ]);

        $this->setSearchPlaceholder('Search referees');
    }

    public function builder(): RefereeBuilder
    {
        return Referee::query()
            ->with('latestEmployment');
    }

    public function columns(): array
    {
        return [
            Column::make('Name')
                ->label(fn ($row, Column $column) => ucwords($row->first_name.' '.$row->last_name))
                ->sortable(),
            Column::make(__('referees.status'), 'status')
                ->view('tables.columns.status'),
            Column::make(__('employments.start_date'), 'latestEmployment.started_at')
                ->label(fn ($row, Column $column) => $row->latestEmployment?->started_at->format('Y-m-d') ?? 'TBD'),
        ];
    }
}
