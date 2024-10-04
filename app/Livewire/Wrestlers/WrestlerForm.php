<?php

declare(strict_types=1);

namespace App\Livewire\Wrestlers;

use App\Models\Wrestler;
use Livewire\Form;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;

class WrestlerForm extends Form
{
    public ?Wrestler $wrestler;

    #[Validate(as: 'wrestlers.name')]
    public $name = '';

    #[Validate(as: 'wrestlers.feet')]
    public int $height_feet;

    #[Validate(as: 'wrestlers.inches')]
    public int $height_inches;

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
            'name' => ['required', 'string', 'min:3', Rule::unique('wrestlers')->ignore($this->wrestler)],
            'height_feet' => ['required', 'integer', 'max:8'],
            'height_inches' => ['required', 'integer', 'max:11'],
            'weight' => ['required', 'integer', 'digits:3'],
            'hometown' => ['required', 'string'],
            'signature_move' => ['nullable', 'string', 'regex:/^[a-zA-Z\s\']+$/'],
            'start_date' => ['nullable', 'string', 'date'],
        ];
    }

    public function getHeight()
    {
        return ($this->height_feet * 12) + $this->height_inches;
    }
}
