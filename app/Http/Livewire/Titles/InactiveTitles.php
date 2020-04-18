<?php

namespace App\Http\Livewire\Titles;

use App\Models\Title;
use Livewire\Component;
use Livewire\WithPagination;

class InactiveTitles extends Component
{
    use WithPagination;

    public $perPage = 10;

    public function render()
    {
        return view('livewire.titles.inactive-titles', [
            'inactiveTitles' => Title::inactive()->withDeactivatedAtDate()->paginate($this->perPage)
        ]);
    }
}
