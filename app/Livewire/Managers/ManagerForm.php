<?php

declare(strict_types=1);

namespace App\Livewire\Managers;

use App\Livewire\Concerns\StandardForm;
use App\Models\Manager;
use Exception;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ManagerForm extends Form
{
    use StandardForm;

    public ?Manager $formModel;

    protected string $formModelType = Manager::class;

    #[Validate(as: 'managers.first_name')]
    public $first_name = '';

    #[Validate(as: 'managers.last_name')]
    public $last_name = '';

    #[Validate(as: 'employments.start_date')]
    public $start_date = '';

    public function rules()
    {
        return [
            'first_name' => ['required', 'string', 'min:3'],
            'last_name' => ['required', 'string', 'min:3'],
            'start_date' => ['nullable', 'string', 'date'],
        ];
    }

    public function save()
    {
        $this->validate();

        $validated = $this->validate();

        try {
            if (isset($this->formModel)) {
                Gate::authorize('update', $this->formModel);
                $this->formModel->fill($validated);
            } else {
                Gate::authorize('create', Manager::class);
                $this->formModel = new Manager;
                $this->formModel->fill($validated);
            }

            return $this->formModel->save();
        } catch (Exception $exception) {
            dd($exception->getMessage());
        }

        return false;
    }
}
