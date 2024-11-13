<?php

declare(strict_types=1);

namespace App\Livewire\Titles;

use App\Models\Title;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class TitlesList extends Component
{
    /**
     * @var array<int>
     */
    public array $selectedTitleIds = [];

    /**
     * @var array<int>
     */
    public array $titleIdsOnPage = [];

    /**
     * Display a listing of the resource.
     */
    public function render(): View
    {
        $query = Title::query()
            ->oldest('name');

        $titles = $query->paginate();

        $this->titleIdsOnPage = $titles->map(fn (Title $title) => (string) $title->id)->toArray();

        return view('livewire.titles.titles-list', [
            'titles' => $titles,
        ]);
    }
}
