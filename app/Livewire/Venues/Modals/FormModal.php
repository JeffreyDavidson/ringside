<?php

declare(strict_types=1);

namespace App\Livewire\Venues\Modals;

use App\Livewire\Venues\VenueForm;
use App\Models\Venue;
use Illuminate\Support\Facades\Gate;
use LivewireUI\Modal\ModalComponent;

class FormModal extends ModalComponent
{
    public ?Venue $venue;

    public VenueForm $form;

    public function mount()
    {
        if (isset($this->venue)) {
            Gate::authorize('update', $this->venue);
            $this->form->setupModel($this->venue);
        } else {
            Gate::authorize('create', Venue::class);
        }
    }

    public function save()
    {
        if ($this->form->save()) {
            $this->dispatch('refreshDatatable');
            $this->closeModal();
        }
    }

    public function getModalTitle(): string
    {
        return isset($this->venue) ? 'Edit '.$this->venue->name : 'Add Venue';
    }

    public function render()
    {
        return view('livewire.venues.modals.form-modal');
    }
}
