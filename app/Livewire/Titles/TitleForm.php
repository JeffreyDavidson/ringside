<?php

declare(strict_types=1);

namespace App\Livewire\Titles;

use App\Livewire\Concerns\StandardForm;
use App\Models\Title;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class TitleForm extends Form
{
    use StandardForm;

    public ?Title $formModel;

    protected string $formModelType = Title::class;

    #[Validate(as: 'titles.name')]
    public $name = '';

    #[Validate(as: 'activations.start_date')]
    public $start_date = '';

    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'ends_with:Title,Titles',
                Rule::unique('titles', 'name'),
            ],
            'activation_date' => ['nullable', 'string', 'date'],
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
                Gate::authorize('create', Title::class);
                $this->formModel = new Title;
                $this->formModel->fill($validated);
            }

            return $this->formModel->save();
        } catch (Exception $exception) {
            dd($exception->getMessage());
        }

        return false;
    }
}
