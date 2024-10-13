<x-tables.header>
    <x-tables.header.container>
        <x-page.heading>Events</x-page.heading>
        <x-tables.header.metadata>
            <x-tables.header.metadata-label>All Events</x-tables.header.metadata-label>
            <x-tables.header.metadata-count class="me-2">
                {{ $this->builder()->count() }}
            </x-tables.header.metadata-count>
            <x-tables.header.metadata-statuses enum="\App\Enums\EventStatus"/>
        </x-tables.header.metadata>
    </x-tables.header.container>

    <x-tables.actions-container>
        <x-buttons.add-new component="events.modals.form-modal">Add Event</x-buttons.add-new>
    </x-tables.actions-container>
</x-tables.header>
