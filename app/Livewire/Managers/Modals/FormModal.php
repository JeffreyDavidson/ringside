<?php

declare(strict_types=1);

namespace App\Livewire\Managers\Modals;

use App\Livewire\Managers\ManagerForm;
use App\Models\Manager;
use Illuminate\Support\Facades\Gate;
use LivewireUI\Modal\ModalComponent;

class FormModal extends ModalComponent
{
    public ?Manager $manager;

    public ManagerForm $form;

    public function mount()
    {
        if (isset($this->manager)) {
            Gate::authorize('update', $this->manager);
            $this->form->setupModel($this->manager);
        } else {
            Gate::authorize('create', Manager::class);
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
        return isset($this->manager) ? 'Edit '.$this->manager->full_name : 'Add Manager';
    }

    public function render()
    {
        return view('livewire.managers.modals.form-modal');
    }
}
