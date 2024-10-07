<?php

declare(strict_types=1);

namespace App\Livewire\Wrestlers\Modals;

use App\Livewire\Wrestlers\WrestlerForm;
use App\Models\Wrestler;
use Illuminate\Support\Facades\Gate;
use LivewireUI\Modal\ModalComponent;

class FormModal extends ModalComponent
{
    public ?Wrestler $model;

    public WrestlerForm $form;

    public function mount()
    {
        if (isset($this->model)) {
            Gate::authorize('update', $this->model);
            $this->form->setupModel($this->model);
        } else {
            Gate::authorize('create', Wrestler::class);
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
        return isset($this->model) ? 'Edit '.$this->model->name : 'Add Wrestler';
    }

    public function render()
    {
        return view('livewire.wrestlers.modals.form-modal');
    }
}
