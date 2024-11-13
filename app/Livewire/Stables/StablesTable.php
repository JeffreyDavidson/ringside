<?php

declare(strict_types=1);

namespace App\Livewire\Stables;

use App\Livewire\Concerns\BaseTableTrait;
use App\Models\Stable;
use Illuminate\Contracts\View\View;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class StablesTable extends DataTableComponent
{
    use BaseTableTrait;

    protected string $databaseTableName = 'stables';

    protected string $routeBasePath = 'stables';

    public function configure(): void
    {
    }

    public function columns(): array
    {
        return [
            Column::make(__('stables.name'), 'name'),
            Column::make(__('stables.status'), 'status')
                ->view('components.tables.columns.status-column'),
            Column::make(__('activations.start_date'), 'start_date'),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function render(): View
    {
        $query = Stable::query()
            ->oldest('name');

        $stables = $query->paginate();

        return view('livewire.stables.stables-list', [
            'stables' => $stables,
        ]);
    }
}
