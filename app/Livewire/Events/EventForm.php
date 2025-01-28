<?php

declare(strict_types=1);

namespace App\Livewire\Events;

use App\Actions\Events\CreateAction;
use App\Actions\Events\UpdateAction;
use App\Data\EventData;
use App\Livewire\Base\LivewireBaseForm;
use App\Models\Event;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Validate;

class EventForm extends LivewireBaseForm
{
    protected string $formModelType = Event::class;

    public ?Event $formModel;

    #[Validate('required|string|min:5|max:255', as: 'events.name')]
    public string $name = '';

    #[Validate('required|date', as: 'events.date')]
    public Carbon|string $date = '';

    #[Validate('required|integer|exists:venue,id', as: 'events.venue')]
    public int $venue;

    #[Validate('required|string', as: 'events.preview')]
    public string $preview;

    public function store(): bool
    {
        $this->validate();

        $data = EventData::fromForm([$this->name, $this->date, $this->venue, $this->preview]);

        if (! isset($this->formModel)) {
            app(CreateAction::class)->handle($data);
        } else {
            app(UpdateAction::class)->handle($this->formModal, $data);
        }

        return true;
    }
}
