<?php

declare(strict_types=1);

namespace App\Livewire\Events\Modals;

use App\Livewire\Events\EventForm;
use App\Models\Event;
use Illuminate\Support\Facades\Gate;
use LivewireUI\Modal\ModalComponent;

class FormModal extends ModalComponent
{
    public ?Event $model;

    public EventForm $form;

    public function mount()
    {
        if (isset($this->model)) {
            Gate::authorize('update', $this->model);
            $this->form->setupModel($this->model);
        } else {
            Gate::authorize('create', Event::class);
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
        return isset($this->model) ? 'Edit '.$this->model->name : 'Add Event';
    }

    public function render()
    {
        return view('livewire.events.modals.form-modal');
    }
}
