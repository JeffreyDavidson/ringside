<?php

declare(strict_types=1);

namespace App\Livewire\Titles;

use App\Livewire\Concerns\BaseTableTrait;
use App\Models\Title;
use Illuminate\Contracts\View\View;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class TitlesTable extends DataTableComponent
{
    use BaseTableTrait;

    protected string $databaseTableName = 'titles';

    protected string $routeBasePath = 'titles';

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

    /**
     * Display a listing of the resource.
     */
    public function render(): View
    {
        $query = Title::query()
            ->oldest('name');

        $titles = $query->paginate();

        return view('livewire.titles.titles-list', [
            'titles' => $titles,
        ]);
    }
}
