<?php

declare(strict_types=1);

namespace App\Livewire\TagTeams;

use App\Models\TagTeam;
use Illuminate\Contracts\View\View;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class TagTeamsList extends DataTableComponent
{
    public function configure(): void
    {
    }

    public function columns(): array
    {
        return [
            Column::make(__('tag-teams.name'), 'name'),
            Column::make(__('tag-teams.status'), 'status')
                ->view('components.tables.columns.status-column'),
            Column::make(__('tag-teams.partners'), 'partners'),
            Column::make(__('tag-teams.combined_weight'), 'combined_weight'),
            Column::make(__('employments.start_date'), 'start_date'),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function render(): View
    {
        $query = TagTeam::query()
            ->oldest('name');

        $tagTeams = $query->paginate();

        return view('livewire.tag-teams.tag-teams-list', [
            'tagTeams' => $tagTeams,
        ]);
    }
}
