<?php

declare(strict_types=1);

namespace App\Livewire\Wrestlers\Modals;

use App\Livewire\Wrestlers\WrestlerForm;
use App\Models\Wrestler;
use Illuminate\Support\Facades\Gate;
use LivewireUI\Modal\ModalComponent;

class FormModal extends ModalComponent
{
    public ?Wrestler $wrestler;

    public WrestlerForm $form;

    public function mount()
    {
        if (isset($this->wrestler)) {
            Gate::authorize('update', $this->wrestler);
            $this->form->setupModel($this->wrestler);
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
        return isset($this->wrestler) ? 'Edit '.$this->wrestler->name : 'Add Wrestler';
    }

    public function render()
    {
        return view('livewire.wrestlers.modals.form-modal');
    }
}
