<?php

declare(strict_types=1);

namespace App\Livewire\Wrestlers;

use App\Builders\StableBuilder;
use App\Livewire\Concerns\ExtraTableTrait;
use App\Models\Stable;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class WrestlerStablesTable extends DataTableComponent
{
    use ExtraTableTrait;

    /**
     * WrestlerId to use for component.
     */
    public int $wrestlerId;

    protected string $databaseTableName = 'stables';

    public function configure(): void {}

    public function builder(): StableBuilder
    {
        return Stable::withWhereHas('wrestlers', function ($query) {
            $query->where('wrestler_id', $this->wrestlerId);
        });
    }

    public function columns(): array
    {
        return [
            Column::make(__('stables.name'))
                ->sortable(),
            Column::make(__('stables.tag_team_partner'))
                ->label(fn ($row, Column $column) => $row->hired_at->format('Y-m-d')),
            Column::make(__('stables.date_joined'))
                ->label(fn ($row, Column $column) => $row->joined_at->format('Y-m-d') ?? 'Current'),
            Column::make(__('stables.date_left'))
                ->label(fn ($row, Column $column) => $row->joined_at->format('Y-m-d') ?? 'Current'),
        ];
    }
}
