<?php

declare(strict_types=1);

namespace App\Livewire\Wrestlers;

use App\Models\Wrestler;
use Illuminate\Support\Facades\Gate;
use LivewireUI\Modal\ModalComponent;

class DeleteWrestler extends ModalComponent
{
    public Wrestler $wrestler;

    public function mount()
    {
        Gate::authorize('delete', $this->wrestler);
    }

    public function delete()
    {
        $this->dispatch('refreshDatatable');

        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.wrestlers.delete-wrestler');
    }
}
