<?php

declare(strict_types=1);

namespace App\Livewire\Titles;

use App\Models\Title;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class TitlesList extends Component
{
    /**
     * Display a listing of the resource.
     */
    public function render(): View
    {
        $query = Title::query()
            ->oldest('name');

        $titles = $query->paginate();

        return view('livewire.titles.titles-list', [
            'titles' => $titles,
        ]);
    }
}
