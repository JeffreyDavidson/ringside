<?php

declare(strict_types=1);

namespace App\Livewire\Referees;

use App\Models\Referee;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class PreviousMatchesList extends Component
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
