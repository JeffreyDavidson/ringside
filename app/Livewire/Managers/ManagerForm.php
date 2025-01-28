<?php

declare(strict_types=1);

namespace App\Livewire\Managers;

use App\Actions\Managers\CreateAction;
use App\Actions\Managers\UpdateAction;
use App\Data\ManagerData;
use App\Livewire\Base\LivewireBaseForm;
use App\Models\Manager;
use App\Rules\EmploymentStartDateCanBeChanged;
use Illuminate\Support\Carbon;

class ManagerForm extends LivewireBaseForm
{
    protected string $formModelType = Manager::class;

    public ?Manager $formModel;

    public string $first_name = '';

    public string $last_name = '';

    public Carbon|string|null $start_date = '';

    protected function rules()
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'start_date' => ['nullable', 'date', new EmploymentStartDateCanBeChanged($this->formModel ?? '')],
        ];
    }

    protected function validationAttributes()
    {
        return [
            'start_date' => 'start date',
        ];
    }

    public function loadExtraData(): void
    {
        $this->start_date = $this->formModel->firstEmployment?->started_at->toDateString();
    }

    public function store(): bool
    {
        $this->validate();

        $data = ManagerData::fromForm([$this->first_name, $this->last_name, $this->start_date]);

        if (! isset($this->formModel)) {
            app(CreateAction::class)->handle($data);
        } else {
            app(UpdateAction::class)->handle($this->formModal, $data);
        }

        return true;
    }
}
