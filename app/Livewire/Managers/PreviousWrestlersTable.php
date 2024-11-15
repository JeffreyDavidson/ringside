<?php

declare(strict_types=1);

namespace App\Livewire\Managers;

use App\Builders\WrestlerBuilder;
use App\Livewire\Concerns\ShowTableTrait;
use App\Models\Manager;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\DateColumn;

class PreviousWrestlersTable extends DataTableComponent
{
    use ShowTableTrait;

    protected string $databaseTableName = 'wrestlers';

    protected string $resourceName = 'wrestlers';

    /**
     * Manager to use for component.
     */
    public Manager $manager;

    /**
     * Set the Manager to be used for this component.
     */
    public function mount(Manager $manager): void
    {
        $this->manager = $manager;
    }

    public function builder():
    {
        return $this->manager->previousWrestlers();
    }

    public function configure(): void
    {
    }

    public function columns(): array
    {
        return [
            Column::make(__('wrestlers.name'), 'name'),
            DateColumn::make(__('wrestlers.date_hired'), 'hired_at')
                ->outputFormat('Y-m-d'),
            DateColumn::make(__('wrestlers.date_left'), 'left_at')
                ->outputFormat('Y-m-d'),
        ];
    }
}
