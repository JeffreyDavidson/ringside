<?php

declare(strict_types=1);

namespace App\Livewire\Stables;

use App\Builders\StableBuilder;
use App\Livewire\Concerns\BaseTableTrait;
use App\Models\Stable;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class StablesTable extends DataTableComponent
{
    use BaseTableTrait;

    protected string $databaseTableName = 'stables';

    protected string $routeBasePath = 'stables';

    public function builder(): StableBuilder
    {
        return Stable::query()
            ->oldest('name');
    }

    public function configure(): void
    {
    }

    public function columns(): array
    {
        return [
            Column::make(__('stables.name'), 'name'),
            Column::make(__('stables.status'), 'status')
                ->view('components.tables.columns.status-column'),
            Column::make(__('activations.start_date'), 'start_date'),
        ];
    }
}
