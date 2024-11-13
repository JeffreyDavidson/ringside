<?php

declare(strict_types=1);

namespace App\Livewire\Managers;

use App\Livewire\Concerns\BaseTableTrait;
use App\Models\Manager;
use Illuminate\Contracts\View\View;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ManagersTable extends DataTableComponent
{
    use BaseTableTrait;

    protected string $databaseTableName = 'managers';

    protected string $routeBasePath = 'managers';

    public function configure(): void
    {
    }

    public function columns(): array
    {
        return [
            Column::make(__('managers.name'), 'name'),
            Column::make(__('managers.status'), 'status')
                ->view('components.tables.columns.status-column'),
            Column::make(__('employments.start_date'), 'start_date'),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function render(): View
    {
        $query = Manager::query()
            ->oldest('last_name');

        $managers = $query->paginate();

        return view('livewire.managers.managers-list', [
            'managers' => $managers,
        ]);
    }
}
