<?php

declare(strict_types=1);

namespace App\Livewire\Wrestlers;

use App\Models\Wrestler;
use Illuminate\Contracts\View\View;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class PreviousTagTeamsList extends DataTableComponent
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
        $query = $this->wrestler
            ->previousTagTeams();

        $previousTagTeams = $query->paginate();

        return view('livewire.wrestlers.previous-tag-teams.previous-tag-teams-list', [
            'previousTagTeams' => $previousTagTeams,
        ]);
    }
}
