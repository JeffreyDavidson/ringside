<?php

namespace App\Http\Livewire;

use Livewire\Component;

class DataTablesTotal extends Component
{
    public $recordsTotal = 0;
    public $entity;

    public function render()
    {
        return view('livewire.data-tables-total');
    }
}
