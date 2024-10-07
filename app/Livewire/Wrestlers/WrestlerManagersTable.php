<?php

declare(strict_types=1);

namespace App\Livewire\Wrestlers;

use App\Builders\ManagerBuilder;
use App\Livewire\Concerns\ExtraTableTrait;
use App\Models\Manager;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class WrestlerManagersTable extends DataTableComponent
{
    use ExtraTableTrait;

    protected string $databaseTableName = 'managers';

    /**
     * WrestlerId to use for component.
     */
    public int $wrestlerId;

    public function configure(): void
    {
        $this->addAdditionalSelects([
            'managers.first_name as first_name',
            'managers.last_name as last_name',
        ]);
    }

    public function builder(): ManagerBuilder
    {
        return Manager::withWhereHas('wrestlers', function ($query) {
            $query->where('wrestler_id', $this->wrestlerId);
        });
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
            Column::make(__('managers.hired_at'), 'manager.hired_at')
                ->label(fn ($row, Column $column) => $row->hired_at->format('Y-m-d')),
            Column::make(__('managers.left_at'), 'manager.left_at')
                ->label(fn ($row, Column $column) => $row->left_at->format('Y-m-d') ?? 'Current'),
        ];
    }
}
