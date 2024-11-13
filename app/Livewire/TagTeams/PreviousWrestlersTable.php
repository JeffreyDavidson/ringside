<?php

declare(strict_types=1);

namespace App\Livewire\TagTeams;

use App\Models\TagTeam;
use Illuminate\Contracts\View\View;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class PreviousWrestlersTable extends DataTableComponent
{
    /**
     * Tag Team to use for component.
     */
    public TagTeam $tagTeam;

    /**
     * Set the Tag Team to be used for this component.
     */
    public function mount(TagTeam $tagTeam): void
    {
        $this->tagTeam = $tagTeam;
    }

    public function configure(): void
    {
    }

    public function columns(): array
    {
        return [
            Column::make(__('wrestlers.name'), 'wrestler_name'),
            Column::make(__('tag-teams.date_joined'), 'date_joined'),
            Column::make(__('tag-teams.date_left'), 'date_left'),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function render(): View
    {
        $query = $this->tagTeam
            ->previousWrestlers();

        $previousWrestlers = $query->paginate();

        return view('livewire.tag-teams.previous-wrestlers.previous-wrestlers-list', [
            'previousWrestlers' => $previousWrestlers,
        ]);
    }
}
