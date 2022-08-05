<?php

declare(strict_types=1);

namespace App\Http\Livewire\Titles;

use App\Http\Livewire\BaseComponent;
use App\Models\Title;
use App\Models\TitleChampionship;

class TitleChampionshipsList extends BaseComponent
{
    /**
     * Undocumented variable.
     *
     * @var \App\Models\Title
     */
    public Title $title;

    /**
     * List of filters that are allowed.
     *
     * @var array<string, string>
     */
    public $filters = [
        'search' => '',
    ];

    /**
     * Undocumented function.
     *
     * @param  \App\Models\Title  $title
     * @return void
     */
    public function mount(Title $title)
    {
        $this->title = $title;
    }

    /**
     * Undocumented function.
     *
     * @return void
     */
    public function getRowsQueryProperty()
    {
        return TitleChampionship::where('title_id', $this->title->id)->latest('won_at');
    }

    /**
     * Undocumented function.
     *
     * @return void
     */
    public function getRowsProperty()
    {
        return $this->applyPagination($this->rowsQuery);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.titles.title-championships-list', [
            'titleChampionships' => $this->rows,
        ]);
    }
}
