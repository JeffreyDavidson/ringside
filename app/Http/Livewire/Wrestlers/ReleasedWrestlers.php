<?php

namespace App\Http\Livewire\Wrestlers;

use App\Models\Wrestler;
use Livewire\Component;
use Livewire\WithPagination;

class ReleasedWrestlers extends Component
{
    use WithPagination;

    /**
     * @var int
     */
    public $perPage = 10;

    /**
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $releasedWrestlers = Wrestler::query()
            ->released()
            ->withReleasedAtDate()
            ->paginate($this->perPage);

        return view('livewire.wrestlers.released-wrestlers', [
            'releasedWrestlers' => $releasedWrestlers,
        ]);
    }
}
