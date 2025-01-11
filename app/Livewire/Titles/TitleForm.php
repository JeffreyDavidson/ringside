<?php

declare(strict_types=1);

namespace App\Livewire\Titles;

use App\Livewire\Base\LivewireBaseForm;
use App\Models\Title;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Validate;

class TitleForm extends LivewireBaseForm
{
    protected string $formModelType = Title::class;

    public ?Title $formModel;

    #[Validate('required|string|min:5|max:255', as: 'titles.name')]
    public string $name = '';

    #[Validate('required|date', as: 'activations.started_at')]
    public Carbon|string $start_date = '';

    public function loadExtraData(): void
    {
        $this->start_date = $this->formModel->currentActivation?->started_at;
    }

    public function store(): bool
    {
        $this->validate();

        if (! isset($this->formModel)) {
            $this->formModel = new Title([
                'name' => $this->name,
            ]);
            $this->formModel->save();
        } else {
            $this->formModel->update([
                'name' => $this->name,
            ]);
        }

        return true;
    }
}
