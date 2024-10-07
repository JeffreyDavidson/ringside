<?php

declare(strict_types=1);

namespace App\Livewire\Wrestlers;

use App\Livewire\Concerns\StandardForm;
use App\Models\Wrestler;
use App\Rules\EmploymentStartDateCanBeChanged;
use App\ValueObjects\Height;
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
            'start_date' => ['nullable', 'string', 'date', new EmploymentStartDateCanBeChanged($wrestler)],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'signature_move.regex' => 'The signature move only allows for letters, spaces, and apostrophes',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'start_date' => 'start date',
            'signature_move' => 'signature move',
        ];
    }

    public function save()
    {
        $validated = $this->validate();

        try {
            if (isset($this->formModel)) {
                Gate::authorize('update', $this->formModel);

                $this->formModel->update([...$validated, ...['height' => $this->getHeight()]]);

                if (isset($validated['start_date'])) {
                    $this->formModel->employments()->updateOrCreate(
                        ['ended_at' => null],
                        ['started_at' => $validated->start_date->toDateTimeString()]
                    );
                }
            } else {
                Gate::authorize('create', Wrestler::class);

                $this->formModel = Wrestler::create([...$validated, ...['height' => $this->getHeight()]]);

                if (isset($validated['start_date'])) {
                    $this->formModel->employments()->create([
                        'started_at' => $validated['start_date']
                    ]);
                }
            }

            return true;

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
        $this->start_date = $model->employments->whereNull('ended_at')->first()->started_at->format("Y-m-d") ?? null;
    }

    protected function runExtraPreSaveMethods(): void
    {
        $this->formModel->height = $this->getHeight();
    }

    public function getHeight(): int
    {
        return (new Height($this->height_feet ?? 0, $this->height_inches ?? 0))->toInches();
    }
}
