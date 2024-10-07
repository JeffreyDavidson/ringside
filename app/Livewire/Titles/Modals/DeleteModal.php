<?php

declare(strict_types=1);

namespace App\Livewire\Titles\Modals;

use App\Models\Title;
use Illuminate\Support\Facades\Gate;
use LivewireUI\Modal\ModalComponent;

class DeleteModal extends ModalComponent
{
    public Title $title;

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
        return view('livewire.titles.modals.delete-modal');
    }
}
