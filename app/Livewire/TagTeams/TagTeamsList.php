<?php

declare(strict_types=1);

namespace App\Livewire\TagTeams;

use App\Models\TagTeam;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class TagTeamsList extends Component
{
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
