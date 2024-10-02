<?php

declare(strict_types=1);

namespace App\Livewire\Wrestlers;

use App\Builders\WrestlerBuilder;
use App\Livewire\Concerns\BaseTableTrait;
use App\Models\Wrestler;
use Illuminate\Support\Facades\Gate;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class WrestlersTable extends DataTableComponent
{
    use BaseTableTrait;

    protected string $databaseTableName = 'wrestlers';

    protected string $routeBasePath = 'wrestlers';

    protected string $modalPath = 'wrestlers.wrestler-modal';

    public function configure(): void
    {
        $this->setConfigurableAreas([
            'before-wrapper' => 'wrestlers.table-pre',
        ]);

        $this->setSearchPlaceholder('Search wrestlers');
    }

    public function builder(): WrestlerBuilder
    {
        return Wrestler::query()
            ->with('latestEmployment');
    }

    public function delete(Wrestler $model)
    {
        try {
            Gate::authorize('delete', $model);
            $model->delete();
        } catch (\Exception $exception) {
            report($exception);
        }
    }

    public function columns(): array
    {
        return [
            Column::make(__('wrestlers.name'), 'name')
                ->sortable()
                ->searchable(),
            Column::make(__('wrestlers.status'), 'status')
                ->view('tables.columns.status'),
            Column::make(__('wrestlers.height'), 'height'),
            Column::make(__('wrestlers.weight'), 'weight'),
            Column::make(__('wrestlers.hometown'), 'hometown'),
            Column::make(__('employments.start_date'), 'latestEmployment.started_at')
                ->label(fn ($row, Column $column) => $row->latestEmployment?->started_at->format('Y-m-d') ?? 'TBD'),
        ];
    }
}
