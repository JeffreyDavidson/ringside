<?php

namespace App\View\Components;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class DataTable extends Component
{
    /**
     * @var \Illuminate\Database\Eloquent\Collection
     */
    public $collection;

    /**
     * Create a new component instance.
     *
     * @param  \Illuminate\Database\Eloquent\Collection $collection
     * @return void
     */
    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.data-table');
    }
}
