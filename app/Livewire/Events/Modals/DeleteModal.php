<?php

declare(strict_types=1);

namespace App\Livewire\Events\Modals;

use App\Models\Event;
use Illuminate\Support\Facades\Gate;
use LivewireUI\Modal\ModalComponent;

class DeleteModal extends ModalComponent
{
    public Event $event;

    public function mount()
    {
        Gate::authorize('delete', $this->model);
    }

    public function delete()
    {
        $this->model->delete();

        $this->dispatch('refreshDatatable');

        $this->closeModal();
    }

    public function getModalTitle(): string
    {
        return 'Delete '.$this->model->name;
    }

    public function render()
    {
        return view('livewire.events.modals.delete-modal');
    }
}
