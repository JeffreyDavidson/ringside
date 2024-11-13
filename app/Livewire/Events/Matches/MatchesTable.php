<?php

declare(strict_types=1);

namespace App\Livewire\Events\Matches;

use App\Livewire\Concerns\BaseTableTrait;
use App\Models\Event;
use App\Models\EventMatch;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class MatchesTable extends DataTableComponent
{
    use BaseTableTrait;

    protected string $databaseTableName = 'event_matches';

    protected string $routeBasePath = 'event-matches';

    /**
     * Event to use for component.
     */
    public Event $event;

    /**
     * Set the Event to be used for this component.
     */
    public function mount(Event $event): void
    {
        $this->event = $event;
    }

    public function builder(): Builder
    {
        return EventMatch::query()
            ->where('event_id', $this->event->id)
            ->oldest('name');
    }

    public function configure(): void
    {
    }

    public function columns(): array
    {
        return [
            Column::make(__('matches.match_type_name'), 'name'),
        ];
    }
}
