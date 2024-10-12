<x-tables.header>
    <x-tables.header.container>
        <x-page.heading>Venues</x-page.heading>
        <x-tables.header.metadata>
            <x-tables.header.metadata-label>All Venues</x-tables.header.metadata-label>
            <x-tables.header.metadata-count>
                {{ $this->builder()->count() }}
            </x-tables.header.metadata-count>
        </x-tables.header.metadata>
    </x-tables.header.container>

    <x-tables.actions-container>
        <x-buttons.add-new component="venues.modals.form-modal">Add Venue</x-buttons.add-new>
    </x-tables.actions-container>
</x-tables.header>
