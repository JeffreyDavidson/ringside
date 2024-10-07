<?php

declare(strict_types=1);

namespace App\Livewire\Titles\Modals;

use App\Livewire\Titles\TitleForm;
use App\Models\Title;
use Illuminate\Support\Facades\Gate;
use LivewireUI\Modal\ModalComponent;

class FormModal extends ModalComponent
{
    public ?Title $model;

    public TitleForm $form;

    public function mount()
    {
        if (isset($this->model)) {
            Gate::authorize('update', $this->model);
            $this->form->setupModel($this->model);
        } else {
            Gate::authorize('create', Title::class);
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
        return isset($this->model) ? 'Edit '.$this->model->name : 'Add Title';
    }

    public function render()
    {
        return view('livewire.titles.modals.form-modal');
    }
}
