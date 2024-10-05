<?php

declare(strict_types=1);

namespace App\Livewire\Venues;

use App\Livewire\Concerns\StandardForm;
use App\Models\Venue;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class VenueForm extends Form
{
    use StandardForm;

    public ?Venue $formModel;

    protected string $formModelType = Venue::class;

    #[Validate(as: 'venues.name')]
    public $name = '';

    #[Validate(as: 'venues.street_address')]
    public ?int $street_address;

    #[Validate(as: 'venues.city')]
    public ?int $height_inches;

    #[Validate(as: 'venues.state')]
    public $state = '';

    #[Validate(as: 'venues.hometown')]
    public $hometown = '';

    #[Validate(as: 'venues.signature_move')]
    public $signature_move = '';

    #[Validate(as: 'employments.start_date')]
    public $start_date = '';

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'min:3', Rule::unique('referees')->ignore(isset($this->formModel) ? $this->formModel : '')],
            'street_address' => ['required', 'string', 'min:3'],
            'city' => ['required', 'string', 'min:3'],
            'state' => ['required', 'string', 'exists:App\Models\State,name'],
            'zipcode' => ['required', 'integer', 'digits:5'],
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
                Gate::authorize('create', Venue::class);
                $this->formModel = new Venue;
                $this->formModel->fill($validated);
            }

            return $this->formModel->save();
        } catch (Exception $exception) {
            dd($exception->getMessage());
        }

        return false;
    }
}
