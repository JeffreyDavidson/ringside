<?php

declare(strict_types=1);

namespace App\Livewire\Wrestlers;

use App\Builders\WrestlerBuilder;
use App\Enums\WrestlerStatus;
use App\Models\Wrestler;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\DateColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class WrestlersTable extends DataTableComponent
{
    public function builder(): WrestlerBuilder
    {
        return Wrestler::query();
    }

    public function bulkActions(): array
    {
        return [
            'exportSelected' => 'Export',
        ];
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setSearchPlaceholder('search wrestlers')
            ->setColumnSelectDisabled()
            ->setPaginationEnabled();
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->sortable(),
            Column::make('Name')
                ->sortable()
                ->searchable(),
            Column::make('Status')
                ->view('status'),
            Column::make('Height'),
            Column::make('Weight'),
            Column::make('Hometown'),
            // DateColumn::make('Start Date', 'currentEmployment.started_at')
            //     ->eagerLoadRelations(),
            Column::make('Action')
                ->label(
                    fn ($row, Column $column) => view('components.livewire.datatables.action-column')->with(
                        [
                            'viewLink' => route('wrestlers.show', $row),
                            'editLink' => route('wrestlers.edit', $row),
                            'deleteLink' => route('wrestlers.destroy', $row),
                        ]
                    )
                )->html(),
        ];
    }

    public function filters(): array
    {
        $statuses = collect(WrestlerStatus::cases())->map(function ($status) {
            return ['value' => $status->value, 'label' => $status->name];
        })->toArray();

        return [
            SelectFilter::make('Status', 'testing')
                ->options(['' => 'Select One', 1 => 'Testing'])
                ->filter(function (Builder $builder, string $value) {
                    $builder->where('status', $value);
                })
        ];
    }
}
