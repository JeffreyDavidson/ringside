<?php

declare(strict_types=1);

namespace App\Livewire\Wrestlers;

use App\Models\Wrestler;
use Illuminate\Contracts\View\View;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class PreviousMatchesTable extends DataTableComponent
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
        $query = $this->wrestler
            ->previousMatches();

        $previousMatches = $query->paginate();

        return view('livewire.wrestlers.previous-matches.previous-matches-list', [
            'previousMatches' => $previousMatches,
        ]);
    }
}
