<?php

declare(strict_types=1);

namespace App\Livewire\Referees\Modals;

use App\Models\Referee;
use Illuminate\Support\Facades\Gate;
use LivewireUI\Modal\ModalComponent;

class DeleteModal extends ModalComponent
{
    public Referee $referee;

    public function mount()
    {
        Gate::authorize('delete', $this->referee);
    }

    public function delete()
    {
        $this->referee->delete();

        $this->dispatch('refreshDatatable');

        $this->closeModal();
    }

    public function getModalTitle(): string
    {
        return 'Delete '.$this->referee->full_name;
    }

    public function render()
    {
        return view('livewire.referees.modals.delete-modal');
    }
}
