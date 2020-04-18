<?php

namespace App\Http\Livewire\Venues;

use App\Models\Venue;
use Livewire\Component;
use Livewire\WithPagination;

class AllVenues extends Component
{
    use WithPagination;

    public $perPage = 10;

    public function paginationView()
    {
        return 'pagination.datatables';
    }

    public function render()
    {
        return view('livewire.venues.all-venues', [
            'venues' => Venue::paginate($this->perPage)
        ]);
    }
}
