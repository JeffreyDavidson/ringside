<?php

declare(strict_types=1);

namespace App\Livewire\TagTeams;

use App\Models\TagTeam;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class TagTeamsList extends Component
{
    /**
     * @var array<int>
     */
    public array $selectedTagTeamIds = [];

    /**
     * @var array<int>
     */
    public array $tagTeamIdsOnPage = [];

    /**
     * Display a listing of the resource.
     */
    public function render(): View
    {
        $query = TagTeam::query()
            ->oldest('name');

        $tagTeams = $query->paginate();

        $this->tagTeamIdsOnPage = $tagTeams->map(fn (TagTeam $tagTeam) => (string) $tagTeam->id)->toArray();

        return view('livewire.tag-teams.tag-teams-list', [
            'tagTeams' => $tagTeams,
        ]);
    }
}
