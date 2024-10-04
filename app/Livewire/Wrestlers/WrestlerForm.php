<?php

declare(strict_types=1);

namespace App\Livewire\Wrestlers;

use App\Livewire\Concerns\StandardForm;
use App\Models\Wrestler;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class WrestlerForm extends Form
{
    use StandardForm;

    public ?Wrestler $formModel;

    protected string $formModelType = Wrestler::class;

    #[Validate(as: 'wrestlers.name')]
    public $name = '';

    #[Validate(as: 'wrestlers.feet')]
    public ?int $height_feet;

    #[Validate(as: 'wrestlers.inches')]
    public ?int $height_inches;

    #[Validate(as: 'wrestlers.weight')]
    public $weight = '';

    #[Validate(as: 'wrestlers.hometown')]
    public $hometown = '';

    #[Validate(as: 'wrestlers.signature_move')]
    public $signature_move = '';

    #[Validate(as: 'employments.start_date')]
    public $start_date = '';

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'min:3', Rule::unique('wrestlers')->ignore(isset($this->formModel) ? $this->formModel : '')],
            'height_feet' => ['required', 'integer', 'max:8'],
            'height_inches' => ['required', 'integer', 'max:11'],
            'weight' => ['required', 'integer', 'digits:3'],
            'hometown' => ['required', 'string'],
            'signature_move' => ['nullable', 'string', 'regex:/^[a-zA-Z\s\']+$/'],
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
                $this->formModel->height = $this->getHeight();
            } else {
                Gate::authorize('create', Wrestler::class);
                $this->formModel = new Wrestler;
                $this->formModel->fill($validated);
                $this->formModel->height = $this->getHeight();
            }

            return $this->formModel->save();
        } catch (Exception $exception) {
            dd($exception->getMessage());
        }

        return false;
    }

    // Over-ride the default empty runExtraLoadMethods with something that actually does some work
    protected function runExtraLoadMethods(Model $model): void
    {
        $this->height_feet = $model->height->feet;
        $this->height_inches = $model->height->inches;
    }

    protected function runExtraPreSaveMethods(): void
    {
        $this->formModel->height = $this->getHeight();
    }

    public function getHeight()
    {
        return (($this->height_feet ?? 0) * 12) + ($this->height_inches ?? 0);
    }
}
