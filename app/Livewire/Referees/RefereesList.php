<?php

declare(strict_types=1);

namespace App\Livewire\Referees;

use App\Models\Referee;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class RefereesList extends Component
{
    /**
     * Display a listing of the resource.
     */
    public function render(): View
    {
        $query = Referee::query()
            ->oldest('last_name');

        $referees = $query->paginate();

        return view('livewire.referees.referees-list', [
            'referees' => $referees,
        ]);
    }
}
