<?php

declare(strict_types=1);

namespace App\Livewire\Wrestlers\Modals;

use App\Models\Wrestler;
use Illuminate\Support\Facades\Gate;
use LivewireUI\Modal\ModalComponent;

class DeleteModal extends ModalComponent
{
    public Wrestler $wrestler;

    public function mount()
    {
        Gate::authorize('delete', $this->wrestler);
    }

    public function delete()
    {
        $this->wrestler->delete();

        $this->dispatch('refreshDatatable');

        $this->closeModal();
    }

    public function getModalTitle(): string
    {
        return 'Delete '.$this->wrestler->name;
    }

    public function render()
    {
        return view('livewire.wrestlers.modals.delete-modal');
    }
}
