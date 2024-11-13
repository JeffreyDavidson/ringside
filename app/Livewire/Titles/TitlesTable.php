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

    public function builder(): TitleBuilder
    {
        return Title::query()
            ->oldest('name');
    }

    public function configure(): void
    {
    }

    public function columns(): array
    {
        return [
            Column::make(__('titles.name'), 'name'),
            Column::make(__('titles.status'), 'status')
                ->view('components.tables.columns.status-column'),
            Column::make(__('titles.current_champion'), 'current_champion'),
            Column::make(__('activations.date_introduced'), 'date_introduced'),
        ];
    }
}
