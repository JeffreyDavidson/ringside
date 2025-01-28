<?php

declare(strict_types=1);

namespace App\Livewire\Stables;

use App\Actions\Stables\CreateAction;
use App\Actions\Stables\UpdateAction;
use App\Data\StableData;
use App\Livewire\Base\LivewireBaseForm;
use App\Models\Stable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Validate;

class StableForm extends LivewireBaseForm
{
    protected string $formModelType = Stable::class;

    public ?Stable $formModel;

    #[Validate('required|string|min:5|max:255', as: 'stables.name')]
    public string $name = '';

    #[Validate('nullable|date', as: 'activations.started_at')]
    public Carbon|string|null $start_date = '';

    #[Validate('nullable|array', as: 'roster.wrestlers')]
    public ?Collection $wrestlers;

    #[Validate('nullable|array', as: 'roster.tag-teams')]
    public ?Collection $tagTeams;

    #[Validate('nullable|array', as: 'roster.managers')]
    public ?Collection $managers;

    public function loadExtraData(): void
    {
        $this->start_date = $this->formModel->firstActivation?->started_at->toDateString();
    }

    public function store(): bool
    {
        $this->validate();

        $data = StableData::fromForm([$this->name, $this->start_date, $this->wrestlers, $this->tagTeams, $this->managers]);

        if (! isset($this->formModel)) {
            app(CreateAction::class)->handle($data);
        } else {
            app(UpdateAction::class)->handle($this->formModal, $data);
        }

        return true;
    }
}
