<?php

declare(strict_types=1);

namespace App\Livewire\Stables;

use App\Models\Stable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class PreviousManagersList extends DataTableComponent
{
    /**
     * Stable to use for component.
     */
    public Stable $stable;

    /**
     * Set the Stable to be used for this component.
     */
    public function mount(Stable $stable): void
    {
        $this->stable = $stable;
    }

    public function configure(): void
    {
    }

    public function columns(): array
    {
        return [
            Column::make(__('managers.name'), 'manager_name'),
            Column::make(__('stables.date_joined'), 'date_joined'),
            Column::make(__('stables.date_left'), 'date_left'),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function render(): View
    {
        $query = $this->stable
            ->previousManagers()
            ->addSelect(
                DB::raw("CONCAT(managers.first_name,' ', managers.last_name) AS full_name"),
            );

        $previousManagers = $query->paginate();

        return view('livewire.stables.previous-managers.previous-managers-list', [
            'previousManagers' => $previousManagers,
        ]);
    }
}
