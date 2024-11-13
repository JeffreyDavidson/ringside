<?php

declare(strict_types=1);

namespace App\Livewire\Wrestlers;

use App\Models\Wrestler;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class PreviousMatchesList extends Component
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
