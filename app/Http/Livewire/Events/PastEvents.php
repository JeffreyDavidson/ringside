<?php

namespace App\Http\Livewire\Events;

use App\Http\Livewire\BaseComponent;
use App\Http\Livewire\DataTable\WithBulkActions;
use App\Http\Livewire\DataTable\WithSorting;
use App\Models\Event;

class PastEvents extends BaseComponent
{
    use WithBulkActions, WithSorting;

    public $showDeleteModal = false;

    public $showFilters = false;

    public $filters = [
        'search' => '',
    ];

    public function deleteSelected()
    {
        $deleteCount = $this->selectedRowsQuery->count();

        $this->selectedRowsQuery->delete();

        $this->showDeleteModal = false;

        $this->notify('You\'ve deleted '.$deleteCount.' titles');
    }

    public function getRowsQueryProperty()
    {
        $query = Event::query()
            ->past();

        return $this->applySorting($query);
    }

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
        return view('livewire.events.past-events', [
            'pastEvents' => $this->rows,
        ]);
    }
}
