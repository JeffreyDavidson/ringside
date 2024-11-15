<?php

declare(strict_types=1);

namespace App\Livewire\Wrestlers;

use App\Builders\ManagerBuilder;
use App\Livewire\Concerns\Columns\HasFullNameColumn;
use App\Livewire\Concerns\ShowTableTrait;
use App\Models\Wrestler;
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

    public function builder(): ManagerBuilder
    {
        // dd($this->wrestler
        // ->previousManagers
        // ->toQuery());
        return $this->wrestler
            ->previousManagers
            ->toQuery();
    }

    public function configure(): void
    {
        $this->setDebugEnabled();
        // $this->addAdditionalSelects([
        //     'managers.first_name as first_name',
        //     'managers.last_name as last_name',
        //     // 'pivot.hired_at as hired_at',
        //     // 'pivot.left_at as left_at',
        // ]);
        // $this->addExtraWith('pivot_hired_at');
        // $this->addExtraWith('pivot_left_at');
    }

    public function columns(): array
    {
        return [
            $this->getDefaultFullNameColumn(),
            DateColumn::make(__('managers.date_hired'), 'pivot_hired_at')
                ->outputFormat('Y-m-d H:i'),
            DateColumn::make(__('managers.date_fired'), 'pivot_left_at')
                ->outputFormat('Y-m-d H:i'),
        ];
    }
}
