<x-tables.header>
    <x-tables.header.container>
        <x-page.heading>Wrestlers</x-page.heading>
        <x-tables.header.metadata>
            <x-tables.header.metadata-label>All Wrestlers</x-tables.header.metadata-label>
            <x-tables.header.metadata-count class="me-2">
                {{ $this->builder()->count() }}
            </x-tables.header.metadata-count>
            <x-tables.header.metadata-label>Bookable</x-tables.header.metadata-label>
            <x-tables.header.metadata-count>
                {{ $this->builder()->where('status', \App\Enums\WrestlerStatus::Bookable->value)->count() }}
            </x-tables.header.metadata-count>
        </x-tables.header.metadata>
    </x-tables.header.container>

    <x-tables.actions-container>
        <x-buttons.add-new component="wrestlers.modals.form-modal">Add Wrestler</x-buttons.add-new>
    </x-tables.actions-container>
</x-tables.header>
