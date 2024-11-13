<?php

declare(strict_types=1);

namespace App\Livewire\Referees;

use App\Models\Referee;
use Illuminate\Contracts\View\View;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class PreviousMatchesList extends DataTableComponent
{
    /**
     * Referee to use for component.
     */
    public Referee $referee;

    /**
     * Set the Referee to be used for this component.
     */
    public function mount(Referee $referee): void
    {
        $this->referee = $referee;
    }

    public function configure(): void
    {
    }

    public function columns(): array
    {
        return [
            Column::make(__('events.name'), 'name'),
            Column::make(__('events.date'), 'date'),
            Column::make(__('matches.opponents'), 'opponents'),
            Column::make(__('matches.titles'), 'titles'),
            Column::make(__('matches.result'), 'result'),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function render(): View
    {
        $query = $this->referee
            ->previousMatches();

        $previousMatches = $query->paginate();

        return view('livewire.referees.previous-matches.previous-matches-list', [
            'previousMatches' => $previousMatches,
        ]);
    }
}
