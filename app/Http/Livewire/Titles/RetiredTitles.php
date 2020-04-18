<?php

namespace App\Http\Livewire\Titles;

use App\Models\Title;
use Livewire\Component;
use Livewire\WithPagination;

class RetiredTitles extends Component
{
    use WithPagination;

    public $perPage = 10;

    public function render()
    {
        return view('livewire.titles.retired-titles', [
            'retiredTitles' => Title::retired()->withRetiredAtDate()->paginate($this->perPage)
        ]);
    }
}
