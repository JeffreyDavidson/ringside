<?php

namespace App\Http\Livewire\Titles;

use App\Models\Title;
use Livewire\Component;
use Livewire\WithPagination;

class PendingAndUnactivatedTitles extends Component
{
    use WithPagination;

    public $perPage = 10;

    public function render()
    {
        $pendingAndUnactivatedTitles = Title::query()
            ->pendingActivation()
            ->orWhere
            ->unactivated()
            ->withActivatedAtDate()
            ->paginate();

        return view('livewire.titles.pending-and-unactivated-titles', [
            'pendingAndUnactivatedTitles' => $pendingAndUnactivatedTitles
        ]);
    }
}
