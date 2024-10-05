<?php

declare(strict_types=1);

namespace App\Livewire\Referees\Modals;

use App\Livewire\Referees\RefereeForm;
use App\Models\Referee;
use Illuminate\Support\Facades\Gate;
use LivewireUI\Modal\ModalComponent;

class FormModal extends ModalComponent
{
    public ?Referee $referee;

    public RefereeForm $form;

    public function mount()
    {
        if (isset($this->referee)) {
            Gate::authorize('update', $this->referee);
            $this->form->setupModel($this->referee);
        } else {
            Gate::authorize('create', Referee::class);
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
        return isset($this->referee) ? 'Edit '.$this->referee->full_name : 'Add Referee';
    }

    public function render()
    {
        return view('livewire.referees.modals.form-modal');
    }
}
