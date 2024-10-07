<?php

declare(strict_types=1);

namespace App\Livewire\Events;

use App\Livewire\Concerns\StandardForm;
use App\Models\Event;
use App\Models\Venue;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class EventForm extends Form
{
    use StandardForm;

    public ?Event $formModel;

    protected string $formModelType = Event::class;

    #[Validate(as: 'events.name')]
    public $name = '';

    #[Validate(as: 'events.venue')]
    public $venue_id = '';

    #[Validate(as: 'date')]
    public $date = '';

    #[Validate(as: 'events.preview')]
    public $preview = '';

    public function showOptions(): array
    {
        return Venue::pluck('name', 'id')->all();
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'min:3', Rule::unique('events')->ignore(isset($this->formModel) ? $this->formModel : '')],
            'venue_id' => ['required', 'integer', Rule::exists('venues', 'id')],
            'date' => ['nullable', 'string', 'date'],
            'preview' => ['required', 'string'],
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
                Gate::authorize('create', Event::class);
                $this->formModel = new Event;
                $this->formModel->fill($validated);
            }

            return $this->formModel->save();
        } catch (Exception $exception) {
            dd($exception->getMessage());
        }

        return false;
    }
}
