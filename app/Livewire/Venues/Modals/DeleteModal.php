<?php

declare(strict_types=1);

namespace App\Livewire\Venues\Modals;

use App\Models\Venue;
use Illuminate\Support\Facades\Gate;
use LivewireUI\Modal\ModalComponent;

class DeleteModal extends ModalComponent
{
    public Venue $venue;

    public function mount()
    {
        Gate::authorize('delete', $this->venue);
    }

    public function delete()
    {
        $this->venue->delete();

        $this->dispatch('refreshDatatable');

        $this->closeModal();
    }

    public function getModalTitle(): string
    {
        return 'Delete '.$this->venue->name;
    }

    public function render()
    {
        return view('livewire.venues.modals.delete-modal');
    }
}
