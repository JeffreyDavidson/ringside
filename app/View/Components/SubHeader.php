<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SubHeader extends Component
{
    public $title;
    public $search;
    public $filters;
    public $link;
    public $linkText;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($title, $search = false, $filters = null, $link, $linkText)
    {
        $this->title = $title;
        $this->search = $search;
        $this->filters = $filters;
        $this->link = $link;
        $this->linkText = $linkText;
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
