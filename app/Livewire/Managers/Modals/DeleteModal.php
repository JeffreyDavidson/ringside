<?php

declare(strict_types=1);

namespace App\Livewire\Managers\Modals;

use App\Models\Manager;
use Illuminate\Support\Facades\Gate;
use LivewireUI\Modal\ModalComponent;

class DeleteModal extends ModalComponent
{
    public Manager $manager;

    public function mount()
    {
        Gate::authorize('delete', $this->manager);
    }

    public function delete()
    {
        $this->manager->delete();

        $this->dispatch('refreshDatatable');

        $this->closeModal();
    }

    public function getModalTitle(): string
    {
        return 'Delete '.$this->manager->full_name;
    }

    public function render()
    {
        return view('livewire.managers.modals.delete-modal');
    }
}
