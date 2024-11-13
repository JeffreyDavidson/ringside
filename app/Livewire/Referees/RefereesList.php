<?php

declare(strict_types=1);

namespace App\Livewire\Referees;

use App\Models\Referee;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class RefereesList extends Component
{
    /**
     * @var array<int>
     */
    public array $selectedRefereeIds = [];

    /**
     * @var array<int>
     */
    public array $refereeIdsOnPage = [];

    /**
     * Display a listing of the resource.
     */
    public function render(): View
    {
        $query = Referee::query()
            ->oldest('last_name');

        $referees = $query->paginate();

        $this->refereeIdsOnPage = $referees->map(fn (Referee $referee) => (string) $referee->id)->toArray();

        return view('livewire.referees.referees-list', [
            'referees' => $referees,
        ]);
    }
}
