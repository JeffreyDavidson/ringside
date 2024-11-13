<?php

declare(strict_types=1);

namespace App\Livewire\Managers;

use App\Models\Manager;
use Illuminate\Contracts\View\View;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class PreviousWrestlersList extends DataTableComponent
{
    /**
     * Manager to use for component.
     */
    public Manager $manager;

    /**
     * Set the Manager to be used for this component.
     */
    public function mount(Manager $manager): void
    {
        $this->manager = $manager;
    }

    public function configure(): void
    {
    }

    public function columns(): array
    {
        return [
            Column::make(__('wrestlers.name'), 'name'),
            Column::make(__('wrestlers.partner'), 'partner'),
            Column::make(__('wrestlers.date_joined'), 'date_joined'),
            Column::make(__('wrestlers.date_left'), 'date_left'),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function render(): View
    {
        $query = $this->manager
            ->previousWrestlers();

        $previousWrestlers = $query->paginate();

        return view('livewire.managers.previous-wrestlers.previous-wrestlers-list', [
            'previousWrestlers' => $previousWrestlers,
        ]);
    }
}
