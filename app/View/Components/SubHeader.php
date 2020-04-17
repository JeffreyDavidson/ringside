<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SubHeader extends Component
{
    public $title;
    public $displayRecordsCount;
    public $search;
    public $filters;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($title, $displayRecordsCount = false, $search = false, $filters = null)
    {
        $this->title = $title;
        $this->displayRecordsCount = $displayRecordsCount;
        $this->search = $search;
        $this->filters = $filters;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.subheader');
    }
}
