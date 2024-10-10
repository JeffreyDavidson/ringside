<?php

declare(strict_types=1);

namespace App\Livewire\Titles;

use App\Builders\TitleBuilder;
use App\Livewire\Concerns\BaseTableTrait;
use App\Models\Title;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class TitlesTable extends DataTableComponent
{
    use BaseTableTrait;

    protected string $databaseTableName = 'titles';

    protected string $routeBasePath = 'titles';

    protected string $formModalPath = 'titles.modals.form-modal';

    protected string $deleteModalPath = 'titles.modals.delete-modal';

    protected string $baseModel = 'title';

    public function configure(): void
    {
        $this->setConfigurableAreas([
            'before-wrapper' => 'components.titles.table-pre',
        ]);

        $this->setSearchPlaceholder('Search titles');
    }

    public function builder(): TitleBuilder
    {
        return Title::query()
            ->with('latestActivation');
    }

    public function columns(): array
    {
        return [
            Column::make(__('titles.name'), 'name')
                ->sortable()
                ->searchable(),
            Column::make(__('titles.status'), 'status')
                ->view('components.tables.columns.status'),
            Column::make(__('activations.start_date'), 'latestActivation.started_at')
                ->label(fn ($row, Column $column) => $row->latestEmployment?->started_at->format('Y-m-d') ?? 'TBD'),
        ];
    }
}
