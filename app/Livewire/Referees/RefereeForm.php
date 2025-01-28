<?php

declare(strict_types=1);

namespace App\Livewire\Referees;

use App\Actions\Referees\CreateAction;
use App\Actions\Referees\UpdateAction;
use App\Data\RefereeData;
use App\Livewire\Base\LivewireBaseForm;
use App\Models\Referee;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Validate;

class RefereeForm extends LivewireBaseForm
{
    protected string $formModelType = Referee::class;

    public ?Referee $formModel;

    #[Validate('required|string|max:255', as: 'referees.first_name')]
    public string $first_name = '';

    #[Validate('required|string|max:255', as: 'referees.last_name')]
    public string $last_name = '';

    #[Validate('nullable|date', as: 'employments.started_at')]
    public Carbon|string|null $start_date = '';

    public function loadExtraData(): void
    {
        $this->start_date = $this->formModel->currentEmployment?->started_at;
    }

    public function store(): bool
    {
        $this->validate();

        $data = RefereeData::fromForm([$this->first_name, $this->last_name, $this->start_date]);

        if (! isset($this->formModel)) {
            app(CreateAction::class)->handle($data);
        } else {
            app(UpdateAction::class)->handle($this->formModal, $data);
        }

        return true;
    }
}
