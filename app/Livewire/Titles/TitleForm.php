<?php

declare(strict_types=1);

namespace App\Livewire\Titles;

use App\Actions\Titles\CreateAction;
use App\Actions\Titles\UpdateAction;
use App\Data\TitleData;
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

    #[Validate('nullable|date', as: 'activations.started_at')]
    public Carbon|string|null $start_date = '';

    public function loadExtraData(): void
    {
        $this->start_date = $this->formModel->currentActivation?->started_at;
    }

    public function store(): bool
    {
        $this->validate();

        $data = TitleData::fromForm([$this->name, $this->start_date]);

        if (! isset($this->formModel)) {
            app(CreateAction::class)->handle($data);
        } else {
            app(UpdateAction::class)->handle($this->formModal, $data);
        }

        return true;
    }
}
