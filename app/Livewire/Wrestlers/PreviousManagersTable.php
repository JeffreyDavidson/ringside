<?php

declare(strict_types=1);

namespace App\Livewire\Wrestlers;

use App\Builders\ManagerBuilder;
use App\Livewire\Concerns\Columns\HasFullNameColumn;
use App\Livewire\Concerns\ShowTableTrait;
use App\Models\Manager;
use App\Models\Wrestler;
use App\Models\WrestlerManager;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Columns\DateColumn;

class PreviousManagersTable extends DataTableComponent
{
    use ShowTableTrait, HasFullNameColumn;

    protected string $databaseTableName = 'managers';

    protected string $resourceName = 'manages';

    /**
     * Wrestler to use for component.
     */
    public Wrestler $wrestler;

    /**
     * Set the Wrestler to be used for this component.
     */
    public function mount(Wrestler $wrestler): void
    {
        $this->wrestler = $wrestler;
    }

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
            $this->getDefaultFullNameColumn(),
            DateColumn::make(__('managers.date_hired'), 'hired_at')
                ->outputFormat('Y-m-d H:i'),
            DateColumn::make(__('managers.date_fired'), 'left_at')
                ->outputFormat('Y-m-d H:i'),
        ];
    }
}
