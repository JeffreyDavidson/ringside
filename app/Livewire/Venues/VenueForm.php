<?php

declare(strict_types=1);

namespace App\Livewire\Venues;

use App\Actions\Venues\CreateAction;
use App\Actions\Venues\UpdateAction;
use App\Data\VenueData;
use App\Livewire\Base\LivewireBaseForm;
use App\Models\Venue;
use Illuminate\Validation\Rule;

class VenueForm extends LivewireBaseForm
{
    protected string $formModelType = Venue::class;

    public ?Venue $formModel;

    public string $name = '';

    public string $street_address = '';

    public string $city = '';

    public string $state = '';

    public int|string $zipcode = '';

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('venues')->ignore($this->formModel ?? '')],
            'street_address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255', Rule::exists('App\Models\State', 'name')],
            'zipcode' => ['required', 'integer', 'digits:5'],
        ];
    }

    protected function validationAttributes()
    {
        return [
            'street_address' => 'street address',
            'zipcode' => 'zip code',
        ];
    }

    public function store(): bool
    {
        $this->validate();

        $data = VenueData::fromForm([$this->name, $this->street_address, $this->city, $this->state, $this->zipcode]);

        if (! isset($this->formModel)) {
            app(CreateAction::class)->handle($data);
        } else {
            app(UpdateAction::class)->handle($this->formModal, $data);
        }

        return true;
    }
}
