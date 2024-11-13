<?php

declare(strict_types=1);

namespace App\Livewire\Referees;

use App\Livewire\Concerns\BaseTableTrait;
use App\Models\Referee;
use Illuminate\Contracts\View\View;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class RefereesTable extends DataTableComponent
{
    use BaseTableTrait;

    protected string $databaseTableName = 'referees';

    protected string $routeBasePath = 'referees';

    public function configure(): void
    {
    }

    public function columns(): array
    {
        return [
            Column::make(__('referees.name'), 'name'),
            Column::make(__('referees.status'), 'status')
                ->view('components.tables.columns.status-column'),
            Column::make(__('employments.start_date'), 'start_date'),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function render(): View
    {
        $query = Referee::query()
            ->oldest('last_name');

        $referees = $query->paginate();

        return view('livewire.referees.referees-list', [
            'referees' => $referees,
        ]);
    }
}
