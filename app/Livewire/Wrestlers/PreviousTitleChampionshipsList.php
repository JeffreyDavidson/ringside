<?php

declare(strict_types=1);

namespace App\Livewire\Wrestlers;

use App\Models\Wrestler;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PreviousTitleChampionshipsList extends Component
{
    /**
     * Wrestler to use for component.
     */
    public Wrestler $wrestler;

    /**
     * Undocumented function.
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
            ->previousTitleChampionships()
            ->with('title')
            ->addSelect(
                'title_championships.title_id',
                'title_championships.won_at',
                'title_championships.lost_at',
                DB::raw('DATEDIFF(COALESCE(lost_at, NOW()), won_at) AS days_held_count')
            );

        $previousTitleChampionships = $query->paginate();

        return view('livewire.wrestlers.previous-title-championships.previous-title-championships-list', [
            'previousTitleChampionships' => $previousTitleChampionships,
        ]);
    }
}
