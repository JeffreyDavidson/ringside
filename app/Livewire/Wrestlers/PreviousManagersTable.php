<?php

declare(strict_types=1);

namespace App\Livewire\Wrestlers;

use App\Builders\ManagerBuilder;
use App\Livewire\Concerns\ShowTableTrait;
use App\Models\Manager;
use App\Models\WrestlerManager;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Columns\DateColumn;

class PreviousManagersTable extends DataTableComponent
{
    use ShowTableTrait;

    protected string $databaseTableName = 'managers';

    protected string $resourceName = 'manages';

    /**
     * Wrestler to use for component.
     */
    public ?int $wrestlerId;

    public function builder(): Builder
    {
        return WrestlerManager::query()
            ->with('manager')
            ->where('wrestler_id', $this->wrestler->id);
    }

    public function configure(): void
    {
        $this->addAdditionalSelects([
            'managers.id',
            'managers.first_name as first_name',
            'managers.last_name as last_name',
        ]);
    }

    public function columns(): array
    {
        return [
            Column::make(__('managers.full_name'), 'manager.full_name')
                ->searchable(),
            DateColumn::make(__('managers.date_hired'), 'hired_at')
                ->outputFormat('Y-m-d H:i'),
            DateColumn::make(__('managers.date_fired'), 'left_at')
                ->outputFormat('Y-m-d H:i'),
        ];
    }
}
