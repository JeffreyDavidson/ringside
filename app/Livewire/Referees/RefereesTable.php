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

    public function builder(): RefereeBuilder
    {
        return Referee::query()
            ->oldest('last_name');
    }

    public function configure(): void
    {
    }

    public function columns(): array
    {
        return [
            Column::make(__('referees.name'), 'name'),
            Column::make(__('referees.status'), 'status')
                ->view('components.tables.columns.status-column'),
            Column::make(__('employments.start_date'), 'start_date'),
        ];
    }
}
