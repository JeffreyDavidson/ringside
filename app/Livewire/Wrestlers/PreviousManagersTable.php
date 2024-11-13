<?php

declare(strict_types=1);

namespace App\Livewire\Wrestlers;

use App\Models\Wrestler;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class PreviousManagersTable extends DataTableComponent
{
    /**
     * Wrestler to use for component.
     */
    public Wrestler $wrestler;

    /**
     * Set the Wrestler to be used for this component.
     */
    public function mount(Wrestler $wrestler): void
    {
        $this->wrestler = $wrestler;
    }

    public function configure(): void {}

    public function columns(): array
    {
        return [
            Column::make(__('managers.name'), 'name'),
            Column::make(__('managers.date_hired'), 'date_hired'),
            Column::make(__('managers.date_fired'), 'date_fired'),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function render(): View
    {
        $query = $this->wrestler
            ->previousManagers()
            ->addSelect(
                DB::raw("CONCAT(managers.first_name,' ', managers.last_name) AS full_name"),
            );

        $previousManagers = $query->paginate();

        return view('livewire.wrestlers.previous-managers.previous-managers-list', [
            'previousManagers' => $previousManagers,
        ]);
    }
}
