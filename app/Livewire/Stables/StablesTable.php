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
    protected string $formModalPath = 'stables.modals.form-modal';
    protected string $deleteModalPath = 'stables.modals.delete-modal';
    protected string $baseModel = 'stable';

    public function configure(): void
    {
    }

    public function builder(): StableBuilder
    {
        return Stable::query()
            ->with('activations:id,started_at')->withWhereHas('activations', function ($query) {
                $query->where('started_at', '<=', now())->whereNull('ended_at')->limit(1);
            });
    }

    public function columns(): array
    {
        return [
            Column::make(__('stables.name'), 'name')
                ->sortable()
                ->searchable(),
            Column::make(__('stables.status'), 'status')
                ->view('tables.columns.status'),
            Column::make(__('activations.start_date'), 'started_at')
                ->label(fn ($row, Column $column) => $row->activations->first()->started_at->format('Y-m-d')),
            Column::make(__('core.actions'), 'actions')
                ->label(
                    fn ($row, Column $column) => view('tables.columns.action-column')->with(
                        [
                            'viewLink' => route('stables.show', $row),
                            'editLink' => route('stables.edit', $row),
                            'deleteLink' => route('stables.destroy', $row),
                        ]
                    )
                )->html(),
        ];
    }
}
