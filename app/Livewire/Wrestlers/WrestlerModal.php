<?php

declare(strict_types=1);

namespace App\Livewire\Wrestlers;

use App\Models\Wrestler;
use Illuminate\Support\Facades\Gate;
use LivewireUI\Modal\ModalComponent;

class WrestlerModal extends ModalComponent
{
    public ?Wrestler $wrestler;

    public WrestlerForm $form;

    public function mount()
    {
        if (isset($this->wrestler)) {
            Gate::authorize('update', $this->wrestler);
            $this->form->fill($this->wrestler);
            $this->form->height_feet = $this->wrestler->height->feet;
            $this->form->height_inches = $this->wrestler->height->inches;

        } else {
            Gate::authorize('create', Wrestler::class);
        }
    }

    public function save()
    {
        $this->form->validate();

        if (isset($this->wrestler)) {
            Gate::authorize('update', $this->wrestler);
            $this->wrestler->fill($this->form->validate());
            $this->wrestler->height = $this->form->getHeight();
            $this->wrestler->save();
        } else {
            Gate::authorize('create', Wrestler::class);
            $this->wrestler = new Wrestler;
            $this->wrestler->fill($this->form->validate());
            $this->wrestler->height = $this->form->getHeight();
            $this->wrestler->save();
        }
    }

    public function render()
    {
        return view('livewire.wrestlers.modal');
    }
}
