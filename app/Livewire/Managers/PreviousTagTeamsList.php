<?php

declare(strict_types=1);

namespace App\Livewire\Managers;

use App\Models\Manager;
use Illuminate\Contracts\View\View;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class PreviousTagTeamsList extends DataTableComponent
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
            Column::make(__('tag-teams.name'), 'name'),
            Column::make(__('tag-teams.partner'), 'partner'),
            Column::make(__('tag-teams.date_joined'), 'date_joined'),
            Column::make(__('tag-teams.date_left'), 'date_left'),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function render(): View
    {
        $query = $this->manager
            ->previousTagTeams();

        $previousTagTeams = $query->paginate();

        return view('livewire.managers.previous-tag-teams.previous-tag-teams-list', [
            'previousTagTeams' => $previousTagTeams,
        ]);
    }
}
